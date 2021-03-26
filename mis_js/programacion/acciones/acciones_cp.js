/*---------------------- GUARDAR ACCION DE CORTO PLAZO ------------------------*/
function valida_envia_acp(){ 
    if (document.formulario.fun_id.value==""){ 
        alertify.alert("SELECCIONE RESPONSABLE DE LA ACCIÓN") 
        document.formulario.fun_id.focus() 
        return 0; 
    }

    if (document.formulario.resultado.value==""){ 
        alertify.alert("REGISTRE ACCIÓN DE CORTO PLAZO") 
        document.formulario.resultado.focus() 
        return 0; 
    }

    if (document.formulario.nro.value==""){ 
        alertify.alert("SELECCIONE NRO DE INDICADORES") 
        document.formulario.nro.focus() 
        return 0; 
    }

    if (document.formulario.pn_cion.value==''){ 
        alertify.alert("REGISTRE VALOR DE PONDERACION") 
        document.formulario.pn_cion.focus() 
        return 0; 
    }

    alertify.confirm("GUARDAR ACCI\u00D3N DE CORTO PLAZO ?", function (a) {
        if (a) {
            //============= GUARDAR DESPUES DE LA VALIDACION ===============
            document.formulario.submit();
            document.getElementById("btsubmit").value = "GUARDANDO...";
            document.getElementById("btsubmit").disabled = true;
            return true;
        } else {
            alertify.error("OPCI\u00D3N CANCELADA");
        }
    }); 
}

function valida_envia_mod_acp(){ 
    if (document.formulario.fun_id.value==""){ 
        alertify.alert("SELECCIONE RESPONSABLE DEL RESULTADO") 
        document.formulario.fun_id.focus() 
        return 0; 
    }

    if (document.formulario.resultado.value==""){ 
        alertify.alert("REGISTRE RESULTADO DE CORTO PLAZO") 
        document.formulario.resultado.focus() 
        return 0; 
    }

    if (document.formulario.pn_cion.value==''){ 
        alertify.alert("REGISTRE VALOR DE PONDERACION") 
        document.formulario.pn_cion.focus() 
        return 0; 
    }

    alertify.confirm("MODIFICAR ACCI\u00D3N DE CORTO PLAZO ?", function (a) {
        if (a) {
            //============= GUARDAR DESPUES DE LA VALIDACION ===============
            document.formulario.submit();
            document.getElementById("btsubmit").value = "MODIFICANDO...";
            document.getElementById("btsubmit").disabled = true;
            return true;
        } else {
            alertify.error("OPCI\u00D3N CANCELADA");
        }
    });     
}
/*--------------------------------- INDICADORES DE CORTO PLAZO ---------------------------*/
function suma_programado()
{
    lb = parseFloat($('[name="lb"]').val());
    m1 = parseFloat($('[name="ms1"]').val());
    m2 = parseFloat($('[name="ms2"]').val());
    m3 = parseFloat($('[name="ms3"]').val());
    m4 = parseFloat($('[name="ms4"]').val());
    m5 = parseFloat($('[name="ms5"]').val());
    m6 = parseFloat($('[name="ms6"]').val());
    m7 = parseFloat($('[name="ms7"]').val());
    m8 = parseFloat($('[name="ms8"]').val());
    m9 = parseFloat($('[name="ms9"]').val());
    m10 = parseFloat($('[name="ms10"]').val());
    m11 = parseFloat($('[name="ms11"]').val());
    m12 = parseFloat($('[name="ms12"]').val());

    $('[name="total"]').val((m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12+lb).toFixed(2) );
}
function suma_programado2()
{
    meta = parseFloat($('[name="met2"]').val());
    $('[name="ms13"]').val((meta).toFixed(2) );
    $('[name="ms14"]').val((meta).toFixed(2) );
    $('[name="ms15"]').val((meta).toFixed(2) );
    $('[name="ms16"]').val((meta).toFixed(2) );
    $('[name="ms17"]').val((meta).toFixed(2) );
    $('[name="ms18"]').val((meta).toFixed(2) );
    $('[name="ms19"]').val((meta).toFixed(2) );
    $('[name="ms20"]').val((meta).toFixed(2) );
    $('[name="ms21"]').val((meta).toFixed(2) );
    $('[name="ms22"]').val((meta).toFixed(2) );
    $('[name="ms23"]').val((meta).toFixed(2) );
    $('[name="ms24"]').val((meta).toFixed(2) );
    

    $('[name="total2"]').val((meta).toFixed(2) );
    $('[name="lb2"]').val((meta).toFixed(2) );
}

function valida_envia_icp(){ 
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
       if (document.formulario.valor_i.value=="0"){ 
            alertify.alert("SELECCIONE EL VALOR DE LA CONSTANTE") 
            document.formulario.valor_i.focus() 
            return 0; 
        }
    }

    if (document.formulario.tp_medida.value=="1") /////// Tipo de Medida lb + Prog = Meta
    { 
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

        if (document.formulario.ms1.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE ENERO") 
            document.formulario.ms1.focus() 
            return 0; 
        }

        if (document.formulario.ms2.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE FEBRERO") 
            document.formulario.ms2.focus() 
            return 0; 
        }

        if (document.formulario.ms3.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE MARZO") 
            document.formulario.ms3.focus() 
            return 0; 
        }

        if (document.formulario.ms4.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE ABRIL") 
            document.formulario.ms4.focus() 
            return 0; 
        }

        if (document.formulario.ms5.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE MAYO") 
            document.formulario.ms5.focus() 
            return 0; 
        }

        if (document.formulario.ms6.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE JUNIO") 
            document.formulario.ms6.focus() 
            return 0; 
        }

        if (document.formulario.ms7.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE JULIO") 
            document.formulario.ms7.focus() 
            return 0; 
        }

        if (document.formulario.ms8.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE AGOSTO") 
            document.formulario.ms8.focus() 
            return 0; 
        }

        if (document.formulario.ms9.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE SEPTIEMBRE") 
            document.formulario.ms9.focus() 
            return 0; 
        }

        if (document.formulario.ms10.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE OCTUBRE") 
            document.formulario.ms10.focus() 
            return 0; 
        }

        if (document.formulario.ms11.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE NOVIEMBRE") 
            document.formulario.ms11.focus() 
            return 0; 
        }

        if (document.formulario.ms12.value==''){ 
            alertify.alert("REGISTRE VALOR - MES DE DICIEMBRE") 
            document.formulario.ms12.focus() 
            return 0; 
        }

        Prog_total=document.formulario.total.value;
        Meta=document.formulario.met.value;
    }
    else{
        if (document.formulario.lb2.value==''){ 
            alertify.alert("REGISTRE VALOR DE LINEA BASE") 
            document.formulario.lb2.focus() 
            return 0; 
        }

        if (document.formulario.met2.value==''){ 
            alertify.alert("REGISTRE VALOR DE META RESULTADO") 
            document.formulario.met2.focus() 
            return 0; 
        }


        Prog_total=document.formulario.total2.value;
        Meta=document.formulario.met2.value;
        if((document.formulario.lb2.value!=0 & document.formulario.met2.value==0) || (parseFloat(document.formulario.lb2.value)>parseFloat(document.formulario.met2.value))|| (parseFloat(document.formulario.lb2.value)<parseFloat(document.formulario.met2.value)))
        {
            alertify.error("INCONSISTENCIA DE VALORES, (LINEA BASE != META) , Verifique Datos") 
            document.formulario.met2.focus() 
            return 0; 
        }
    }

    if (document.formulario.pn_cion.value==''){ 
            alertify.alert("REGISTRE VALOR DE PONDERACION") 
            document.formulario.pn_cion.focus() 
            return 0; 
        }

    if(parseFloat(Meta)==parseFloat(Prog_total)){
        alertify.confirm("GUARDAR DATOS DEL INDICADOR ?", function (a) {
            if (a) {
                //============= GUARDAR DESPUES DE LA VALIDACION ===============
                document.formulario.submit();
                document.getElementById("btsubmit").value = "GUARDANDO...";
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