/*-------------------- CONTROL SUMA GESTIONES --------------------*/
function suma_presupuesto()
{
    if(document.ins_form_nuevo.gestiones.value==1)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        $('[name="suma_monto_total"]').val((a1).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1)).toFixed(2) );

        if(document.ins_form_nuevo.gestv1.value==document.ins_form_nuevo.gv.value){

        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a1).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==2)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        $('[name="suma_monto_total"]').val((a1+a2).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2)).toFixed(2) );

        if(document.ins_form_nuevo.gestv2.value==document.ins_form_nuevo.gv.value){

        a2 = parseFloat($('[id="gestion2"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a2).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==3)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3)).toFixed(2) );

        if(document.ins_form_nuevo.gestv3.value==document.ins_form_nuevo.gv.value){

        a3 = parseFloat($('[id="gestion3"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a3).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==4)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4)).toFixed(2) );

        if(document.ins_form_nuevo.gestv4.value==document.ins_form_nuevo.gv.value){

        a4 = parseFloat($('[id="gestion4"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a4).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==5)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5)).toFixed(2) );

        if(document.ins_form_nuevo.gestv5.value==document.ins_form_nuevo.gv.value){

        a5 = parseFloat($('[id="gestion5"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a5).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==6)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6)).toFixed(2) );

        if(document.ins_form_nuevo.gestv6.value==document.ins_form_nuevo.gv.value){

        a6 = parseFloat($('[id="gestion6"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a6).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==7)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7)).toFixed(2) );

        if(document.ins_form_nuevo.gestv7.value==document.ins_form_nuevo.gv.value){

        a7 = parseFloat($('[id="gestion7"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a7).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==8)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8)).toFixed(2) );

        if(document.ins_form_nuevo.gestv8.value==document.ins_form_nuevo.gv.value){

        a8 = parseFloat($('[id="gestion8"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a8).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==9)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        a9 = parseFloat($('[id="gestion9"]').val()); //// Novena Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8+a9).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8+a9)).toFixed(2) );

        if(document.ins_form_nuevo.gestv9.value==document.ins_form_nuevo.gv.value){

        a9 = parseFloat($('[id="gestion9"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a9).toFixed(2) );
       }
    }

    if(document.ins_form_nuevo.gestiones.value==10)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        a9 = parseFloat($('[id="gestion9"]').val()); //// Novena Gestion
        a10 = parseFloat($('[id="gestion10"]').val()); //// DEcima Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8+a9+a10).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8+a9+a10)).toFixed(2) );

        if(document.ins_form_nuevo.gestv10.value==document.ins_form_nuevo.gv.value){

        a10 = parseFloat($('[id="gestion10"]').val()); //// Primera Gestion
        $('[name="gp"]').val((a10).toFixed(2) );
       }
    }
} 

    /*----------------------- ENVIA REQUERIMIENTO --------------------*/
    function costo_total()
    { 
        a = parseFloat($('[name="ins_cantidad"]').val()); //// Meta
        b = parseFloat($('[name="ins_costo_unitario"]').val()); //// Costo
        if (a!=0 && a>0 )
        {
            $('[name="ins_costo_total"]').val((b*a).toFixed(2) );
            $('[name="ins_costo_total2"]').val((b*a).toFixed(2) );
        }
    }
        
    /*---------------------- VALIDA NUEVO INSUMO (ACTIVOS FIJOS) ----------------------*/
        function valida_envia()
        { 
            if (document.ins_form_nuevo.ins_detalle.value.length==0) /////// Detalle
            { 
              alert("REGISTRE DETALLE DEL INSUMO") 
              document.ins_form_nuevo.ins_detalle.focus() 
              return 0; 
            }

            if (document.ins_form_nuevo.ins_unidad_medida.value.length==0) /////// Unidad de Medida
            { 
              alert("REGISTRE UNIDAD DE MEDIDA") 
              document.ins_form_nuevo.ins_unidad_medida.focus() 
              return 0; 
            }

            if (document.ins_form_nuevo.ins_cantidad.value==0) /////// Cantidad
            { 
              alert("REGISTRE CANTIDAD REQUERIDA") 
              document.ins_form_nuevo.ins_cantidad.focus() 
              return 0; 
            }

            if (document.ins_form_nuevo.ins_costo_unitario.value==0) /////// Costo Unitario
            { 
              alert("REGISTRE COSTO UNITARIO") 
              document.ins_form_nuevo.ins_costo_unitario.focus() 
              return 0; 
            }

            if (document.ins_form_nuevo.ins_partidas.value=="" || document.ins_form_nuevo.ins_partidas.value==0) /////// Partidas
            { 
              alert("SELECCIONE PARTIDAS") 
              document.ins_form_nuevo.ins_partidas.focus() 
              return 0; 
            }

            if (document.ins_form_nuevo.ins_partidas_dependientes.value=="") /////// Partidas
            { 
              alert("SELECCIONE PARTIDAS DEPENDIENTES") 
              document.ins_form_nuevo.ins_partidas_dependientes.focus() 
              return 0; 
            }

           // alert(parseFloat(document.ins_form_nuevo.ins_costo_total.value)+'--'+parseFloat(document.ins_form_nuevo.saldo_fin.value))
            if (parseFloat(document.ins_form_nuevo.gp.value)>parseFloat(document.ins_form_nuevo.saldo_fin.value)) /////// Verificando que el costo total sea <= a saldo por programar
            { 
              alert("Error !!! Monto Programado de la Gestion Actual no puede ser mayor a saldo del Insumo") 
              document.ins_form_nuevo.ins_costo_unitario.focus() 
              return 0; 
            }

           if (parseFloat(document.ins_form_nuevo.suma_monto_total.value)>parseFloat(document.ins_form_nuevo.ins_costo_total.value)) /////// Verificando que el costo total sea <= a saldo por programar
            { 
              alert("Error !!! La suma programado no puede ser mayor al costo total Programado, verifique los valores") 
              document.ins_form_nuevo.ins_costo_unitario.focus() 
              return 0; 
            }

            var OK = confirm("GUARDAR REQUERIMIENTO ?");
            if (OK) {
                    document.ins_form_nuevo.submit(); 
                    document.getElementById("btsubmit").value = "GUARDANDO REQUERIMIENTO...";
                    document.getElementById("btsubmit").disabled = true;
                    return true;
            }

        }
    /*---------------------------------------------------------------------*/
    /*------------------------ ENVIA INSUMO DELEGADO PROGRAMADO -----------------------------*/
        function valida_envia_programado()
        { 
           // alert(parseInt(document.ins_form_prog.gestion.value)
            if(document.ins_form_prog.cant_fin.value==1)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                }
               // alert(parseInt(document.ins_form_prog.gestion.value)+'--'+parseInt(document.ins_form_prog.gestion_vigente.value))
                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value)){
                    alert('Error !! El Monto Asignado1 no puede superar al Monto por programado del 1er Requerimiento !! ') 
                    document.ins_form_prog.ins_monto1.focus() 
                    return 0;
                    }
                }
                
            }
            if(document.ins_form_prog.cant_fin.value==2)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                }
            }
            if(document.ins_form_prog.cant_fin.value==3)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }  
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                }
            }
            if(document.ins_form_prog.cant_fin.value==4)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }   
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                }
                
            }
            if(document.ins_form_prog.cant_fin.value==5)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }   
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                }
            }
            if(document.ins_form_prog.cant_fin.value==6)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }    
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                }

            }
            if(document.ins_form_prog.cant_fin.value==7)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value)+parseFloat(document.ins_form_prog.ins_monto7.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto7.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 7 !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }     
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto7.value)>parseFloat(document.ins_form_prog.saldo_monto7.value))
                    {
                        alert('Error !! El Monto Asignado7 no puede superar al Monto por programar del 7er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                }

            }
            if(document.ins_form_prog.cant_fin.value==8)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value)+parseFloat(document.ins_form_prog.ins_monto7.value)+parseFloat(document.ins_form_prog.ins_monto8.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto7.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 7 !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto8.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 8 !! ') 
                        document.ins_form_prog.ins_monto8.focus() 
                        return 0;
                    }      
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto7.value)>parseFloat(document.ins_form_prog.saldo_monto7.value))
                    {
                        alert('Error !! El Monto Asignado7 no puede superar al Monto por programar del 7er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto8.value)>parseFloat(document.ins_form_prog.saldo_monto8.value))
                    {
                        alert('Error !! El Monto Asignado8 no puede superar al Monto por programar del 8er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto8.focus() 
                        return 0;
                    }
                }

            }
            if(document.ins_form_prog.cant_fin.value==9)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value)+parseFloat(document.ins_form_prog.ins_monto7.value)+parseFloat(document.ins_form_prog.ins_monto8.value)+parseFloat(document.ins_form_prog.ins_monto9.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto7.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 7 !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto9.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 9 !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }      
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto7.value)>parseFloat(document.ins_form_prog.saldo_monto7.value))
                    {
                        alert('Error !! El Monto Asignado7 no puede superar al Monto por programar del 7er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto8.value)>parseFloat(document.ins_form_prog.saldo_monto8.value))
                    {
                        alert('Error !! El Monto Asignado8 no puede superar al Monto por programar del 8er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto8.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto9.value)>parseFloat(document.ins_form_prog.saldo_monto9.value))
                    {
                        alert('Error !! El Monto Asignado9 no puede superar al Monto por programar del 9er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }
                }
            }
            if(document.ins_form_prog.cant_fin.value==10)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value)+parseFloat(document.ins_form_prog.ins_monto7.value)+parseFloat(document.ins_form_prog.ins_monto8.value)+parseFloat(document.ins_form_prog.ins_monto9.value)+parseFloat(document.ins_form_prog.ins_monto10.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto7.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 7 !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto9.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 9 !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto10.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 10 !! ') 
                        document.ins_form_prog.ins_monto10.focus() 
                        return 0;
                    }        
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto7.value)>parseFloat(document.ins_form_prog.saldo_monto7.value))
                    {
                        alert('Error !! El Monto Asignado7 no puede superar al Monto por programar del 7er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto8.value)>parseFloat(document.ins_form_prog.saldo_monto8.value))
                    {
                        alert('Error !! El Monto Asignado8 no puede superar al Monto por programar del 8er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto8.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto9.value)>parseFloat(document.ins_form_prog.saldo_monto9.value))
                    {
                        alert('Error !! El Monto Asignado9 no puede superar al Monto por programar del 9er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto10.value)>parseFloat(document.ins_form_prog.saldo_monto10.value))
                    {
                        alert('Error !! El Monto Asignado10 no puede superar al Monto por programar del 10mo Requerimiento !! ') 
                        document.ins_form_prog.ins_monto10.focus() 
                        return 0;
                    }
                }

            }
            if(document.ins_form_prog.cant_fin.value==11)
            {
                document.getElementById('suma_monto_total').value=parseFloat(document.ins_form_prog.ins_monto1.value)+parseFloat(document.ins_form_prog.ins_monto2.value)+parseFloat(document.ins_form_prog.ins_monto3.value)+parseFloat(document.ins_form_prog.ins_monto4.value)+parseFloat(document.ins_form_prog.ins_monto5.value)+parseFloat(document.ins_form_prog.ins_monto6.value)+parseFloat(document.ins_form_prog.ins_monto7.value)+parseFloat(document.ins_form_prog.ins_monto8.value)+parseFloat(document.ins_form_prog.ins_monto9.value)+parseFloat(document.ins_form_prog.ins_monto10.value)+parseFloat(document.ins_form_prog.ins_monto11.value);  
                if(isNaN(document.getElementById('suma_monto_total').value))
                {
                    if(isNaN(document.ins_form_prog.ins_monto1.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 1 !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto2.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 2 !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto3.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 3 !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto4.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 4 !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    } 
                    if(isNaN(document.ins_form_prog.ins_monto5.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 5 !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto6.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 6 !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto7.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 7 !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto9.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 9 !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto10.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 10 !! ') 
                        document.ins_form_prog.ins_monto10.focus() 
                        return 0;
                    }
                    if(isNaN(document.ins_form_prog.ins_monto11.value))
                    {
                        alert('Verificar Valores Mensuales del Monto Asignado 11 !! ') 
                        document.ins_form_prog.ins_monto11.focus() 
                        return 0;
                    }          
                }

                if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                    if(parseFloat(document.ins_form_prog.ins_monto1.value)>parseFloat(document.ins_form_prog.saldo_monto1.value))
                    {
                        alert('Error !! El Monto Asignado1 no puede superar al Monto por programar del 1er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto1.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto2.value)>parseFloat(document.ins_form_prog.saldo_monto2.value))
                    {
                        alert('Error !! El Monto Asignado2 no puede superar al Monto por programar del 2er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto2.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto3.value)>parseFloat(document.ins_form_prog.saldo_monto3.value))
                    {
                        alert('Error !! El Monto Asignado3 no puede superar al Monto por programar del 3er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto3.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto4.value)>parseFloat(document.ins_form_prog.saldo_monto4.value))
                    {
                        alert('Error !! El Monto Asignado4 no puede superar al Monto por programar del 4er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto4.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto5.value)>parseFloat(document.ins_form_prog.saldo_monto5.value))
                    {
                        alert('Error !! El Monto Asignado5 no puede superar al Monto por programar del 5er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto5.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto6.value)>parseFloat(document.ins_form_prog.saldo_monto6.value))
                    {
                        alert('Error !! El Monto Asignado6 no puede superar al Monto por programar del 6er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto6.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto7.value)>parseFloat(document.ins_form_prog.saldo_monto7.value))
                    {
                        alert('Error !! El Monto Asignado7 no puede superar al Monto por programar del 7er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto7.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto8.value)>parseFloat(document.ins_form_prog.saldo_monto8.value))
                    {
                        alert('Error !! El Monto Asignado8 no puede superar al Monto por programar del 8er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto8.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto9.value)>parseFloat(document.ins_form_prog.saldo_monto9.value))
                    {
                        alert('Error !! El Monto Asignado9 no puede superar al Monto por programar del 9er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto9.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto10.value)>parseFloat(document.ins_form_prog.saldo_monto10.value))
                    {
                        alert('Error !! El Monto Asignado10 no puede superar al Monto por programar del 10mo Requerimiento !! ') 
                        document.ins_form_prog.ins_monto10.focus() 
                        return 0;
                    }
                    if(parseFloat(document.ins_form_prog.ins_monto11.value)>parseFloat(document.ins_form_prog.saldo_monto11.value))
                    {
                        alert('Error !! El Monto Asignado11 no puede superar al Monto por programar del 11er Requerimiento !! ') 
                        document.ins_form_prog.ins_monto11.focus() 
                        return 0;
                    }
                }

            }

            monto_total=parseFloat(document.getElementById('suma_monto_total').value);

           // alert(parseInt(document.ins_form_prog.gestion.value)+'----'+parseInt(document.ins_form_prog.gestion_vigente.value))
            if(parseInt(document.ins_form_prog.gestion.value)==parseInt(document.ins_form_prog.gestion_vigente.value)){
                if(monto_total==document.ins_form_prog.c_prog_gest.value)
                {
                    var OK = confirm('GUARDAR PROGRAMACIÓN DE REQUERIMIENTO '+document.ins_form_prog.gestion.value+' ?');
                    if (OK) {
                        document.ins_form_prog.submit(); 
                        document.getElementById("btsubmit").value = "GUARDANDO PROGRAMADO REQUERIMIENTO...";
                        document.getElementById("btsubmit").disabled = true;
                        return true;
                    }
                }
                else
                {
                    alert('ERROR !!! (SUMA PROGRAMADO : '+monto_total+') != (COSTO TOTAL PROGRAMADO GESTION : '+document.ins_form_prog.c_prog_gest.value+')')
                    
                }
            }
            else{
                var OK = confirm('GUARDAR PROGRAMACIÓN DE REQUERIMIENTO '+document.ins_form_prog.gestion.value+' ?');
                    if (OK) {
                        document.ins_form_prog.submit(); 
                        document.getElementById("btsubmit").value = "GUARDANDO PROGRAMADO REQUERIMIENTO...";
                        document.getElementById("btsubmit").disabled = true;
                        return true;
                    }
            }
            
            
          //  alert(monto_total)
    }

