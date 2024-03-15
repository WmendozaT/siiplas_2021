<div id="main" role="main">
    <div id="">
        <ol class="breadcrumb">
            <li>SESIÓN</li>
            <li>ROL</li>
        </ol>
    </div>
    <div id="content">
        <div class="row">
            <div class="col-md-12">
                <style type="text/css">
                    dl {
                        width: 100%;
                    }
                    dt, dd {
                        padding: 6px;
                    }
                    dt {
                        background: #333333;
                        color: white;
                        border-bottom: 1px solid #141414;
                        border-top: 1px solid #4E4E4E;
                        font: icon;
                        align-content: center;
                        cursor: pointer;
                    }
                    dd {
                        background: #F5F5F5;
                        line-height: 1.6em;
                    }
                    dt.activo, dt:hover {
                        background:#008B8B;
                    }
                    dt:before {
                        /*content: "+";*/
                        margin-right: 20px;
                        font-size: 20px;
                    }
                    dt.activo:before {
                        /*content: "-";*/
                        margin-right: 20px;
                        font-size: 15px;
                    }
                    /*iconos */
                    .btn{  transition-duration: 0.5s; height: 30px;}
                    .btn:hover{transform: scale(1.2);}
                </style>
                <div class="row">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-10 animated fadeInDown" align="center">
                        <div class="alert alert-block alert-success" style="background-color: #568A89; color:white; height:50px; margin: 0 auto;">
                            <h4>
                                <i class="icon fa fa-check" style="color:#e7f3ff;">&nbsp;</i>
                                <?php echo $listar_rol[0]['r_nombre'];?>
                            </h4>
                        </div>
                    </div>
                </div><br>
                <form method="post" action="<?php echo base_url() ?>index.php/mod_opc" style="font-size:18px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-success fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Programación</strong>
                            </div>
                            <?php echo $rol1;?>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Modificaciones</strong>
                            </div>
                            <?php echo $rol2;?>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Registro de Ejecución</strong>
                            </div>
                            <?php echo $rol3;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-warning fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Gerencia de Proyectos</strong>
                            </div>
                            <?php echo $rol4;?>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Reportes</strong>
                            </div>
                            <?php echo $rol7;?>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-danger fade in" style="margin-bottom:4px;">
                                <i class="fa-fw fa fa-check"></i>
                                <strong>Mantenimiento</strong>
                            </div>
                            <?php echo $rol9;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $botton;?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
    </div>
</div>