<style>
    .container {
    position: relative;
    max-width: 650px;
    margin: 0px auto;
    margin-top: 50px;
    }

    .comment {
    background-color: blue;
    float: left;
    width: 100%;
    height: auto;
    }

    .commenter {
    float: left;
    }

    .commenter img {
    width: 35px;
    height: 35px;
    }

    .comment-text-area {
    float: left;
    width: calc(100% - 35px);
    height: auto;
    background-color: red;
    }

    .textinput {
    float:left;
    width: 100%;
    min-height: 35px;
    outline: none;
    resize: none;
    border: 1px solid #f0f0f0;
    }
</style>
<script xmlns="http://www.w3.org/1999/html">
    function abreVentana(PDF) {
        var direccion;
        direccion = '' + PDF;
        window.open(direccion, "Reporte de Proyectos", "width=800,height=650,scrollbars=SI");
    };
</script>
<?php $site_url = site_url(""); ?>
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Inicio</li><li>Marco Estrategico</li><li>Visi&acute;n Institucional</li>
        </ol>
    </div>
    <div id="content">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10 animated fadeInDown" align="center">
                <div class="alert alert-block alert-success" style="background-color: #568A89; color:white; height:50px; margin: 0 auto;">
                    <h4 style=""><i class="icon fa fa-check" style="color:#e7f3ff;">&nbsp;</i>VISIÓN INSTITUCIONAL</h4>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <div class="jarviswidget well jarviswidget-sortable" id="wid-id-1" role="widget" style="border:outset;">
                    <div role="content">
                        <div class="widget-body">
                            <div class="jumbotron">
                                <form class="form-horizontal" action="<?php echo $site_url . '/programacion/vision/editar_vision' ?>" id="form_vision" name="form_vision" method="post">
                                    <h1>
                                        Visión
                                        <a href="javascript:abreVentana('<?php echo $site_url.'/reportes/reporte/mision_vision';?>');" class="btn btn-labeled btn-success pull-right" title="REPORTE">
                                            <span class="btn-label">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </span>
                                            <font>REPORTE</font>
                                        </a>
                                    </h1>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="comment-text-area">
                                                <textarea id="vvision" name="vvision" class="textinput" placeholder="Ingrese Misión" rows="12" disable><?php  echo $vision[0]['conf_vision'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success">
                                                <a href="<?php echo $site_url.'/vision'?>">
                                                    <button id="edit" class="btn btn-primary" type="button">
                                                        Cancelar
                                                    </button>
                                                </a>
                                                <button id="save" class="btn btn-primary" type="submit" >
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
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