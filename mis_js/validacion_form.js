//================================= OBTENER MIS DATOS DE SESION ==============================================
var base_url = document.getElementById("base_url").value;
var site_url = document.getElementById("site_url").value;
var gestion = document.getElementById("gestion").value;
//alert(gestion);
//======================================SCRIPT DE ALERTAS=====================================================
/*document.getElementById("observacion").oninput = function() {
  this.value = this.value.replace(/[<>'"]/g, '');
};*/

function reset() {
    ruta_alerta = base_url + 'assets/themes_alerta/alertify.default.css';
    $("#toggleCSS").attr("href", ruta_alerta);
    alertify.set({
        labels: {
            ok: "ACEPTAR",
            cancel: "CANCELAR"
        },
        delay: 5000,
        buttonReverse: false,
        buttonFocus: "ok"
    });
}
/*-------------------- VALIDACION -------------------*/
//validar que solo ingresen letras sin caracteres especiales incluye ñ
function soloCaracteres_wil(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz0123456789";
    especiales = '';

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function soloLetras(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz-";
    especiales = '';

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}
//funcion permite el ingreso solo de numeros
function soloNumeros(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789";
    especiales = '';

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}
//funcion permite el ingreso de decimales
function numerosDecimales(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789";
    especiales = [46];

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}
//funcion para el ingreso de solo letras con caracteres especiales
function soloLetras_carracter_especial(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz-";
    especiales = [8, 37, 39, 46,45];

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if (letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function justNumbers(e){
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
             
            return /\d/.test(String.fromCharCode(keynum));
        }
        
function limpia() {
    var val = document.getElementById("miInput").value;
    var tam = val.length;
    for (i = 0; i < tam; i++) {
        if (!isNaN(val[i]))
            document.getElementById("miInput").value = '';
    }
}
//funcion para colocar puntos en nuestro numero
function format(input)
{
    var num = input.value.replace(/\./g,'');
    if(!isNaN(num)){
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        input.value = num;
    }

    else{ alert('Solo se permiten numeros');
        input.value = input.value.replace(/[^\d\.]*/g,'');
    }
}
//=========================VALIDADION DE FECHAS==================
function IsNumeric(valor)
{
    var log=valor.length; var sw="S";
    for (x=0; x<log; x++)
    { v1=valor.substr(x,1);
        v2 = parseInt(v1);
//Compruebo si es un valor numérico
        if (isNaN(v2)) { sw= "N";}
    }
    if (sw=="S") {return true;} else {return false; }
}
var primerslap=false;
var segundoslap=false;
function formateafecha(fecha)
{
    var long = fecha.length;
    var dia;
    var mes;
    var ano;
    if ((long>=2) && (primerslap==false)) { dia=fecha.substr(0,2);
        if ((IsNumeric(dia)==true) && (dia<=31) && (dia!="00")) { fecha=fecha.substr(0,2)+"/"+fecha.substr(3,7); primerslap=true; }
        else { fecha=""; primerslap=false;}
    }
    else
    { dia=fecha.substr(0,1);
        if (IsNumeric(dia)==false)
        {fecha="";}
        if ((long<=2) && (primerslap=true)) {fecha=fecha.substr(0,1); primerslap=false; }
    }
    if ((long>=5) && (segundoslap==false))
    { mes=fecha.substr(3,2);
        if ((IsNumeric(mes)==true) &&(mes<=12) && (mes!="00")) { fecha=fecha.substr(0,5)+"/"+fecha.substr(6,4); segundoslap=true; }
        else { fecha=fecha.substr(0,3);; segundoslap=false;}
    }
    else { if ((long<=5) && (segundoslap=true)) { fecha=fecha.substr(0,4); segundoslap=false; } }
    if (long>=7)
    { ano=fecha.substr(6,4);
        if (IsNumeric(ano)==false) { fecha=fecha.substr(0,6); }
        else { if (long==10){ if ((ano==0) || (ano<1900) || (ano>2100)) { fecha=fecha.substr(0,6); } } }
    }
    if (long>=10)
    {
        fecha=fecha.substr(0,10);
        dia=fecha.substr(0,2);
        mes=fecha.substr(3,2);
        ano=fecha.substr(6,4);
// Año no viciesto y es febrero y el dia es mayor a 28
        if ( (ano%4 != 0) && (mes ==12) && (dia > 28) ) { fecha=fecha.substr(0,2)+"/"; }
    }
    return (fecha);
}
//=============================================================