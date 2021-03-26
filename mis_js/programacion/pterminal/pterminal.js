/*----------------------- RESULTADOS DE CORTO PLAZO --------------------------*/
$("#tipo_i").change(function () {
    $("#tipo_i option:selected").each(function () {
        elegido = $(this).val();
        if(elegido == 2 ){
            //ABSOLUTO
            $('#rel').slideDown();
            $('#rel2').slideDown();
            $('#titulo_indicador').html(' RELATIVO ');
            $('#m1').html('%');
            $('#m2').html('%');
            $('#m3').html('%');
            $('#m4').html('%');
            $('#m5').html('%');
        }
        else
        {
            $('#rel').slideUp();
            $('#rel2').slideUp();
            $('#titulo_indicador').html(' ABSOLUTO ');
            $('#m1').html('');
            $('#m2').html('');
            $('#m3').html('');
            $('#m4').html('');
            $('#m5').html('');
            
        }
    });
});

/*--------------------- PRODUCTO TERMINAL ANUAL --------------------*/
function suma_programado_anual(){
    lb = parseFloat($('[name="lb"]').val());
    g1 = parseFloat($('[name="g1"]').val());
    g2 = parseFloat($('[name="g2"]').val());
    g3 = parseFloat($('[name="g3"]').val());
    g4 = parseFloat($('[name="g4"]').val());
    g5 = parseFloat($('[name="g5"]').val());

    $('[name="total"]').val((g1+g2+g3+g4+g5+lb).toFixed(2) );
}

function suma_programado_mensual(){
    lb = parseFloat($('[name="lb"]').val());
    m1 = parseFloat($('[name="m1"]').val());
    m2 = parseFloat($('[name="m2"]').val());
    m3 = parseFloat($('[name="m3"]').val());
    m4 = parseFloat($('[name="m4"]').val());
    m5 = parseFloat($('[name="m5"]').val());
    m6 = parseFloat($('[name="m6"]').val());
    m7 = parseFloat($('[name="m7"]').val());
    m8 = parseFloat($('[name="m8"]').val());
    m9 = parseFloat($('[name="m9"]').val());
    m10 = parseFloat($('[name="m10"]').val());
    m11 = parseFloat($('[name="m11"]').val());
    m12 = parseFloat($('[name="m12"]').val());

    $('[name="total"]').val((m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12+lb).toFixed(2) );
}

function valida_envia_pterminal_multi(){ 
    if (document.formulario.fun_id.value==""){ 
        alertify.alert("SELECCIONE RESPONSABLE DEL PRODUCTO TERMINAL") 
        document.formulario.fun_id.focus() 
        return 0; 
    }

    if (document.formulario.pterminal.value==""){ 
        alertify.alert("REGISTRE PRODUCTO TERMINAL") 
        document.formulario.pterminal.focus() 
        return 0; 
    }

    if (document.formulario.tipo_i.value==""){ 
        alertify.alert("SELECCIONE TIPO DE INDICADOR") 
        document.formulario.tipo_i.focus() 
        return 0; 
    }

    if (document.formulario.lb.value==""){ 
        alertify.alert("REGISTRE LINEA BASE") 
        document.formulario.lb.focus() 
        return 0; 
    }

    if (document.formulario.met.value==""){ 
        alertify.alert("REGISTRE META") 
        document.formulario.met.focus() 
        return 0; 
    }

    if (document.formulario.pn_cion.value==""){ 
        alertify.alert("REGISTRE PONDERACION") 
        document.formulario.pn_cion.focus() 
        return 0; 
    }

    if (document.formulario.g1.value==''){ 
        alertify.alert("REGISTRE VALOR GESTION 1") 
        document.formulario.g1.focus() 
        return 0; 
    }

    if (document.formulario.g2.value==''){ 
        alertify.alert("REGISTRE VALOR GESTION 2") 
        document.formulario.g2.focus() 
        return 0; 
    }

    if (document.formulario.g3.value==''){ 
        alertify.alert("REGISTRE VALOR GESTION 3") 
        document.formulario.g3.focus() 
        return 0; 
    }

    if (document.formulario.g4.value==''){ 
        alertify.alert("REGISTRE VALOR GESTION 4") 
        document.formulario.g4.focus() 
        return 0; 
    }

    if (document.formulario.g5.value==''){ 
        alertify.alert("REGISTRE VALOR GESTION 5") 
        document.formulario.g5.focus() 
        return 0; 
    }

    if(parseFloat(document.formulario.met.value)==parseFloat(document.formulario.total.value)){
        alertify.confirm("GUARDAR PRODUCTO TERMINAL ?", function (a) {
            if (a) {
                document.getElementById("btsubmit").value = "GUARDANDO PRODUCTO...";
                document.getElementById("btsubmit").disabled = true;
                document.formulario.submit();
                
                return true;
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    }
    else{
        if(parseFloat(document.formulario.met.value)>parseFloat(document.formulario.total.value)){
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MENOR A LA META DEL PRODUCTO TERMINAL')
            document.formulario.met.focus() 
            return 0; 
        }
        else{
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MAYOR A LA META DEL PRODUCTO TERMINAL')
            document.formulario.met.focus() 
            return 0; 
        }
    }

    }   


/*-------------------- PRODUCTO TERMINAL MENSUAL ------------------*/
function suma_programado(){
    lb = parseFloat($('[name="lb"]').val());
    m1 = parseFloat($('[name="m1"]').val());
    m2 = parseFloat($('[name="m2"]').val());
    m3 = parseFloat($('[name="m3"]').val());
    m4 = parseFloat($('[name="m4"]').val());
    m5 = parseFloat($('[name="m5"]').val());
    m6 = parseFloat($('[name="m6"]').val());
    m7 = parseFloat($('[name="m7"]').val());
    m8 = parseFloat($('[name="m8"]').val());
    m9 = parseFloat($('[name="m9"]').val());
    m10 = parseFloat($('[name="m10"]').val());
    m11 = parseFloat($('[name="m11"]').val());
    m12 = parseFloat($('[name="m12"]').val());

    $('[name="total"]').val((m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12+lb).toFixed(2) );
}


function valida_envia(){ 
    if (document.formulario.fun_id.value==""){ 
        alertify.alert("SELECCIONE RESPONSABLE DEL PRODUCTO TERMINAL") 
        document.formulario.fun_id.focus() 
        return 0; 
    }

    if (document.formulario.prod.value.length==0){ 
        alertify.alert("REGISTRE PRODUCTO TERMINAL") 
        document.formulario.prod.focus() 
        return 0; 
    }

    if (document.formulario.tipo_i.value==""){ 
        alertify.alert("SELECCIONE TIPO DE INDICADORES") 
        document.formulario.tipo_i.focus() 
        return 0; 
    }

    if (document.formulario.lb.value==''){ 
        alertify.alert("REGISTRE LINEA BASE") 
        document.formulario.lb.focus() 
        return 0; 
    }

    if (document.formulario.met.value=='0' || document.formulario.met.value==''){ 
        alertify.alert("REGISTRE META") 
        document.formulario.met.focus() 
        return 0; 
    }

    if(parseFloat(document.formulario.met.value)==parseFloat(document.formulario.total.value)){
        alertify.confirm("GUARDAR PRODUCTO TERMINAL ?", function (a) {
            if (a) {
                //============= GUARDAR DESPUES DE LA VALIDACION ===============
                document.formulario.submit();
                document.getElementById("btsubmit").value = "GUARDANDO PRODUCTO TERMINAL...";
                document.getElementById("btsubmit").disabled = true;
                return true;
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    }
    else{
        if(parseFloat(document.formulario.met.value)>parseFloat(document.formulario.total.value)){
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MENOR A LA META DEL PRODUCTO')
            document.formulario.met.focus() 
            return 0; 
        }
        else{
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MAYOR A LA META DEL PRODUCTO')
            document.formulario.met.focus() 
            return 0; 
        }
    }       
}


function valida_envia_temporalidad(){ 

    if(parseFloat(document.formulario.g_id.value)==parseFloat(document.formulario.total.value)){
        alertify.confirm("GUARDAR TEMPORALIDAD DE PRODUCTO TERMINAL DE CORTO PLAZO ?", function (a) {
            if (a) {
                document.getElementById("btsubmit").value = "GUARDANDO TEMPORALIDAD...";
                document.getElementById("btsubmit").disabled = true;
                document.formulario.submit();
                
                return true;
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    }
    else{
        if(parseFloat(document.formulario.g_id.value)>parseFloat(document.formulario.total.value)){
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MENOR A LO PROGRAMADO DE LA GESTION VIGENTE')
            document.formulario.total.focus() 
            return 0; 
        }
        else{
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MAYOR A LO PROGRAMADO DE LA GESTION VIGENTE')
            document.formulario.total.focus() 
            return 0; 
        }
    }

} 