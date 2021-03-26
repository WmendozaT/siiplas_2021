
/*--------------------------- INDICADORES ------------------------------*/
function suma_programado(){
    lb = parseFloat($('[name="lb"]').val());
    g1 = parseFloat($('[name="g1"]').val());
    g2 = parseFloat($('[name="g2"]').val());
    g3 = parseFloat($('[name="g3"]').val());
    g4 = parseFloat($('[name="g4"]').val());
    g5 = parseFloat($('[name="g5"]').val());

    $('[name="total"]').val((g1+g2+g3+g4+g5+lb).toFixed(2) );
}

function suma_programado2(){
    meta = parseFloat($('[name="met2"]').val());
    $('[name="g6"]').val((meta).toFixed(2));
    $('[name="g7"]').val((meta).toFixed(2));
    $('[name="g8"]').val((meta).toFixed(2));
    $('[name="g9"]').val((meta).toFixed(2));
    $('[name="g10"]').val((meta).toFixed(2));


    $('[name="total2"]').val((meta).toFixed(2));
    $('[name="lb2"]').val((meta).toFixed(2));
}

function valida_envia_indicador(){ 
    if (document.formulario.tipo_i.value==""){ 
        alertify.alert("SELECCIONE EL TIPO DE INDICADOR") 
        document.formulario.tipo_i.focus() 
        return 0; 
    }

    if (document.formulario.tp_medida.value=="0"){ 
        alertify.alert("SELECCIONE TIPO DE MEDIDA") 
        document.formulario.tp_medida.focus() 
        return 0; 
    }

    if (document.formulario.indicador.value.length==0){ 
        alertify.alert("REGISTRE INDICADOR") 
        document.formulario.indicador.focus() 
        return 0; 
    }

    if (document.formulario.tipo_i.value=="2"){ 
       if (document.formulario.valor_i.value=="0") /////// Tipo de indicadores
        { 
            alertify.alert("SELECCIONE EL VALOR DE LA CONSTANTE") 
            document.formulario.valor_i.focus() 
            return 0; 
        }
    }

    if (document.formulario.lb.value==''){ 
        alertify.alert("REGISTRE VALOR DE LINEA BASE") 
        document.formulario.lb.focus() 
        return 0; 
    }

    if (document.formulario.met.value==''){ 
        alertify.alert("REGISTRE VALOR DE META RESULTADO") 
        document.formulario.met.focus() 
        return 0; 
    }

    if (document.formulario.tp_medida.value=="1") /////// Tipo de Medida lb + Prog = Meta
    { 
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

        Prog_total=document.formulario.total.value;
        Meta=document.formulario.met.value;
    }
    else{
        Prog_total=document.formulario.total2.value;
        Meta=document.formulario.met2.value;
        if((document.formulario.lb2.value!=0 & document.formulario.met2.value==0) || (parseFloat(document.formulario.lb2.value)>parseFloat(document.formulario.met2.value))|| (parseFloat(document.formulario.lb2.value)<parseFloat(document.formulario.met2.value)))
        {
            alertify.error("INCONSISTENCIA DE VALORES, (LINEA BASE != META) , Verifique Datos") 
            document.formulario.met2.focus() 
            return 0; 
        }
    }

    if(parseFloat(Meta)==parseFloat(Prog_total)){
        alertify.confirm("GUARDAR INDICADOR ?", function (a) {
            if (a) {
                //============= GUARDAR DESPUES DE LA VALIDACION ===============
                document.formulario.submit();
                document.getElementById("btsubmit").value = "GUARDANDO INDICADOR...";
                document.getElementById("btsubmit").disabled = true;
                return true;
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    }
    else{
        if(parseFloat(document.formulario.met.value)>parseFloat(document.formulario.total.value)){
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MENOR A LA META DEL RESULTADO')
            document.formulario.met.focus() 
            return 0; 
        }
        else{
            alertify.error('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MAYOR A LA META DEL RESULTADO')
            document.formulario.met.focus() 
            return 0; 
        }
    }
        
}