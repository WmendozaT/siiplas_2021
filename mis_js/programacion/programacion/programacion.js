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
