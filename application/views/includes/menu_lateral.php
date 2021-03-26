<style type="text/css">
    .img-circle{ width: 80px;}
</style>
<div id="shortcut" style="display: none; font-size:15px;">
    <ul style="margin-left:20px;">
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/1/" class="jarvismetro-tile big-cubes bg-color-greenLight">
                <span class="iconbox"> 
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/programacion.png"/>
                    <span>
                        Programación
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/2/" class="jarvismetro-tile big-cubes bg-color-blueDark"> 
                <span class="iconbox">
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/registro1.png" />
                    <span>
                        Modificaciones
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/3/" class="jarvismetro-tile big-cubes bg-color-purple">
                <span class="iconbox">
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/trabajo_social.png" />
                    <span>
                        Registro Ejecución
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/4/" class="jarvismetro-tile big-cubes bg-color-orangeDark">
                <span class="iconbox"> 
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/gerencia.png" />
                    <span>
                        Gerencia de Proyectos
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span> 
            </a>
        </li>
        <li>
            <a href="#wafer" class="jarvismetro-tile big-cubes bg-color-greenLight"> 
                <span class="iconbox">
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/sig.png" />
                    <span>
                        SIG
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/7/" class="jarvismetro-tile big-cubes bg-color-pinkDark">
                <span class="iconbox">
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/impresora.png" />
                    <span>
                        Reportes
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url(); ?>index.php/admin/dm/9/" class="jarvismetro-tile big-cubes selected bg-color-blueDark">
                <span class="iconbox">
                    <img class="img-circle" src="<?php echo base_url(); ?>assets/img/mantenimiento1.png" />
                    <span>
                        Mantenimiento
                        <span class="label pull-right bg-color-darken"></span>
                    </span>
                </span>
            </a>
        </li>
    </ul>
</div>
<!-- Left panel : Navigation area -->
<aside id="left-panel">
    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is -->
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <i class="fa fa-user" aria-hidden="true" style="font-size:20px;"></i>
                <span>
                    <?php echo $this->session->userdata("user_name"); ?>
                </span>
                <i class="fa fa-angle-down"></i>
            </a>
        </span>
    </div>
    <nav>
        <ul>
            <li class="">
                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL">
                    <i class="fa fa-lg fa-fw fa-home"></i>
                    <span class="menu-item-parent">MENÚ PRINCIPAL</span>
                </a>
            </li>
            <li class="text-center">
                <a href="<?php //echo site_url("admin").'/dashboard'; ?>" title="MANTENIMIENTO">
                    <span class="menu-item-parent">
                        <?php echo $titulo;?>
                    </span>
                </a>
            </li>
            <?php
            for ($i = 0; $i < count($enlaces); $i++) {
                if (count($subenlaces[$enlaces[$i]['o_child']]) > 0) {
                    ?>
                    <li>
                        <a href="#">
                            <i class="<?php echo $enlaces[$i]['o_image'] ?>"></i>
                            <span class="menu-item-parent">
                                <?php echo $enlaces[$i]['o_titulo']; ?>
                            </span>
                        </a>
                        <ul>
                            <?php
                            foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url($item['o_url']); ?>">
                                        <?php echo $item['o_titulo']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                }
            } ?>
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>
</aside>