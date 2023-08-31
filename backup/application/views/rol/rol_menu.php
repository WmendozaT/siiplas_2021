

    <!-- MAIN PANEL -->
    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li>SESIÒN</li>
                <li>ROL</li>
            </ol>
        </div>
        <!-- END RIBBON -->

        <!-- MAIN CONTENT -->
        <div id="content">
        <div class="row"><!--row-->
            <div class="col-md-12">
            <style type="text/css">
            dl {
                width: 100%;
            }
            dt, dd {
                padding: 15px;
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
                content: "+";
                margin-right: 20px;
                font-size: 20px;
            }
            dt.activo:before {
                content: "-";
                margin-right: 20px;
                font-size: 20px;
            }
            /*iconos */
            .btn{  transition-duration: 0.5s; }
            .btn:hover{transform: scale(1.2);}
        </style>
            <table WIDTH="100%">
                <tr>
                    <td WIDTH="30%"><h1>Roles</h1></td><td WIDTH="70%"> <h1>Menu</h1></td>
                </tr>
                <?php echo $rol;?>
            </table>
            
            </div>
        </div><!--end row-->
        </div>
        <!--END CONTENT-->
    </div>
    <!--END MAIN PANEL-->

                                
