    $("#est").change(function () {
        $("#est option:selected").each(function () {
            elegido = $(this).val();

            if(elegido == 2 ) /// EJECUCION
            {
                $('#contrato').slideUp();
                $('#ejec').slideUp();
                $('#observaciones').slideDown();
                $('#cerrado').slideUp();
                $("#dep").change(function () {
                    $("#dep option:selected").each(function () {
                    elegido2 = $(this).val();

                    if(elegido2 == 1 ){
                        $('#ejec').slideDown();
                    }
                    else
                    {
                        $('#ejec').slideUp();
                    }
                    });
                });
            }

            if(elegido == 1 ) //PROCESO DE INICIO
            {
                $('#contrato').slideUp();
                $('#ejec').slideUp();
                $('#observaciones').slideDown();
                $('#cerrado').slideUp();
                $("#dep").change(function () {
                    $("#dep option:selected").each(function () {
                    elegido2 = $(this).val();

                    if(elegido2 == 8 ){
                        $('#contrato').slideDown();
                    }
                    else
                    {
                        $('#contrato').slideUp();
                    }
                    });
                });
            }
            if(elegido == 3 ) // EN PROCESO DE CONCLUSION
            {
                $('#contrato').slideUp();
                $('#ejec').slideUp();
                $('#observaciones').slideDown();
                $('#cerrado').slideUp();
                $("#dep").change(function () {
                    $("#dep option:selected").each(function () {
                    elegido2 = $(this).val();

                    if(elegido2 == 2 ){
                        $('#ejec').slideDown();
                    }
                    else
                    {
                        $('#ejec').slideUp();
                    }
                    });
                });
            }
            if(elegido == 4 ) //// Conclusiones
            {
                $('#contrato').slideUp();
                $('#ejec').slideUp();
                $("#dep").change(function () {
                    $("#dep option:selected").each(function () {
                    elegido2 = $(this).val();

                    if(elegido2 == 13 ){
                        $('#cerrado').slideDown();
                        $('#observaciones').slideUp();
                    }
                    else
                    {
                        $('#cerrado').slideUp();
                    }
                    });
                });
                
            }
           
        });
    });

    /*----------------------- RESULTADOS ESTRATEGICOS --------------------------*/
    $("#tipo_i").change(function () {
        $("#tipo_i option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 2 ){
                //ABSOLUTO
                $('#rel').slideDown();
                $('#rel2').slideDown();
                $('#medida').slideDown();
                $('#titulo_indicador').html(' RELATIVO ');
            }
            else
            {
                $('#rel').slideUp();
                $('#rel2').slideUp();
                $('#medida').slideDown();
                $('#titulo_indicador').html(' ABSOLUTO ');
                
            }
        });
    });

    $("#medida").change(function () {
        $("#medida option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 2 ){
                //ABSOLUTO
                $('#prog1').slideUp();
                $('#prog2').slideDown();
                $('#lb1').slideUp();
                $('#lb2').slideDown();
            }
            else
            {
                $('#prog1').slideDown();
                $('#prog2').slideUp();
                $('#lb1').slideDown();
                $('#lb2').slideUp();
            }
        });
    });

        $("#valor_i").change(function () {
        $("#valor_i option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                $('#m1').html(' %');
                $('#m2').html(' %');
                $('#m3').html(' %');
                $('#m4').html(' %');
                $('#m5').html(' %');
                $('#m6').html(' %');
                $('#m7').html(' %');
                $('#m8').html(' %');
                $('#m9').html(' %');
                $('#m10').html(' %');
            }
            if(elegido == 2 ){
                $('#m1').html('/1.000');
                $('#m2').html('/1.000');
                $('#m3').html('/1.000');
                $('#m4').html('/1.000');
                $('#m5').html('/1.000');
                $('#m6').html('/1.000');
                $('#m7').html('/1.000');
                $('#m8').html('/1.000');
                $('#m9').html('/1.000');
                $('#m10').html('/1.000');
            }
            if(elegido == 3 ){
                $('#m1').html('/10.000');
                $('#m2').html('/10.000');
                $('#m3').html('/10.000');
                $('#m4').html('/10.000');
                $('#m5').html('/10.000');
                $('#m6').html('/10.000');
                $('#m7').html('/10.000');
                $('#m8').html('/10.000');
                $('#m9').html('/10.000');
                $('#m10').html('/10.000');
            }
            if(elegido == 4 ){
                $('#m1').html('/100.000');
                $('#m2').html('/100.000');
                $('#m3').html('/100.000');
                $('#m4').html('/100.000');
                $('#m5').html('/100.000');
                $('#m6').html('/100.000');
                $('#m7').html('/100.000');
                $('#m8').html('/100.000');
                $('#m9').html('/100.000');
                $('#m10').html('/100.000');
            }
            if(elegido == 0 ){
                $('#m1').html(' ');
                $('#m2').html(' ');
                $('#m3').html(' ');
                $('#m4').html(' ');
                $('#m5').html(' ');
                $('#m6').html(' ');
                $('#m7').html(' ');
                $('#m8').html(' ');
                $('#m9').html(' ');
                $('#m10').html(' ');
            }
        });
    });
    /*---------------------------------------------------------------------------*/

/*======================================= PROGRAMACION ===========================*/
$("#tp_id").change(function () {
        $("#tp_id option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                //ABSOLUTO
                $('#pi').slideDown();
                $('#pr').slideUp();
                $('#pi1').slideDown();
                $('#pr1').slideUp();
                $('#tg').slideDown();
                $('#op_act').slideUp();
                $('#tp_cp').slideDown();
            }
            if(elegido == 2 ){
                //ABSOLUTO
                $('#pr').slideDown();
                $('#pi').slideUp();
                $('#pr1').slideDown();
                $('#pi1').slideUp();
                $('#tg').slideDown();
                $('#op_act').slideUp();
                $('#tp_cp').slideUp();
            }
            if(elegido == 3 ){
                //ABSOLUTO
                $('#pr').slideDown();
                $('#pi').slideUp();
                $('#pr1').slideDown();
                $('#pi1').slideUp();
                $('#tg').slideDown();
                $('#op_act').slideUp();
                $('#tp_cp').slideUp();
            }
            if(elegido == 4 ){
                //ABSOLUTO
                $('#pr').slideDown();
                $('#pi').slideUp();
                $('#pr1').slideDown();
                $('#pi1').slideUp();
                $('#tg').slideDown();
                $('#op_act').slideDown();
                $('#tp_cp').slideUp();
            }
        });
    });

$("#tp_id2").change(function () {
        $("#tp_id2 option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                //ABSOLUTO
                $('#pi2').slideDown();
                $('#pr2').slideUp();
                $('#op_act').slideUp();
                $('#tp_cp').slideDown();
            }
            if(elegido == 2 ||  elegido == 3)
            {
                $('#pr2').slideDown();
                $('#pi2').slideUp();
                $('#op_act').slideUp();
                $('#tp_cp').slideUp();
            }
            if(elegido == 4)
            {
                $('#pr2').slideDown();
                $('#pi2').slideUp();
                $('#op_act').slideDown();
                $('#tp_cp').slideUp();
            }
        });
    });

/*=============== PROGRAMACION LOCALIZACION ===========================*/
