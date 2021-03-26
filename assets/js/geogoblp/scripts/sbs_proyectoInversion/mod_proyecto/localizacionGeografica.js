//PENDIENTE: realizar  una revision a todo el codigo de localizacion geografica

$(inicializaEventos);
	
function inicializaEventos(){
//    cargaDep();
}
	
function adicionarDep(objdiv){
    if (objdiv == "div_add_dep") 
        cargaDep();
    x= document.getElementById(objdiv);
    $(x).slideDown();
}
	
function ocultaAddDep(objdiv)
{
    x= document.getElementById(objdiv);
    $(x).slideUp();	
}	

function cargaDep()
{
    cod_territorio = document.getElementById("cod_territorio");
    removeAllOptions(cod_territorio);
    param = "";
    datos = ajax_json_init_s("departamento_cb", param);
    cant = datos.length;
    for (i=0 ; i<cant ; i++)
    {
        addOption(cod_territorio, datos[i].territorio, datos[i].cod_territorio, false );
    }
}
	
function cargaMunic(divObj)
{
    x = document.getElementById(divObj);
    cod_territorio = $(x).attr("id_n2");
    objSelect = $(x).find("select");
    auxName = $(objSelect).attr("id");
    auxSel = document.getElementById(auxName);
    removeAllOptions(auxSel);
		
    param = "cod_territorio=" + cod_territorio;
    //alert (param);
    //return;
    datos = ajax_json_init_s("muncipio_cb", param);
    cant = datos.length;
    for (i=0 ; i<cant ; i++)
    {
        addOption(auxSel, datos[i].territorio, datos[i].cod_territorio, false );
    }

    y= document.getElementById(divObj);
    $(y).slideDown();			
		
    //adicionarDep(divObj);			
    getDatosTerritorio(auxSel);	
		
}
	
//function cargaComun(divObj)
//{
//    //alert(divObj);
//    adicionarDep(divObj);
//    x = document.getElementById(divObj);
//    cod_territorio = $(x).attr("id_n2");
//    objSelect = $(x).find("select");
//    auxName = $(objSelect).attr("id");
//    auxSel = document.getElementById(auxName);
//    removeAllOptions(auxSel);		
//    param = "cod_territorio=" + cod_territorio;
//    datos = ajax_json_init_s("obtie_comun_json", param);
//    cant = datos.length;
//    //alert("No existen datos almacenados");
//    for (i=0 ; i<cant ; i++)
//    {
//        addOption(auxSel, datos[i].territorio, datos[i].cod_territorio, false );
//    }	
//}
	
function grabaDep(){
    var iilg_porce;
    var cod_territorio;

    porcentaje = 0;
    cod_territorio = document.getElementById("cod_territorio");

    if (cod_territorio.value == ''){
        alert("Debe seleccionar un territorio");
        return;
    }					
		
    param="cod_territorio=" + 	cod_territorio.value + "&porcentaje_cobertura=" + porcentaje;
    datos = ajax_json_init_s("territorio_graba", param);
    if (datos[0].msg == '1'){
        local_geogr_ajax();
    }
    else
    {
        alert ("Ocurrió un Error :" + datos[0].detail);
    }
				
}
	
	
function grabaDep2()
{
    var vt = $("#cod_territorio").attr("value");
    var vpc = $("#iilg_porce").attr("value");
    $.post("territorio_graba.php",{
        cod_territorio:vt, 
        porcentaje_cobertura:vpc
    },llegadaDatos); 
    return false;		
}
 
function llegadaDatos(datos)
{
    if (datos[0].msg == '1')
    {
        //window.location.reload(); 
        local_geogr_ajax();
    }
    else
    {
        alert ("Ocurrió un Error :" + datos[0].detail);
    }				
}
	
function grabaMun(divObj)
{
    x= document.getElementById(divObj);
    auxObj = $(x).find("input");		
    iilg_porce = $(auxObj).attr("value");
    cod_dep_terr = $(auxObj).attr("territorio");
		
    auxObj = $(x).find("select");
    cod_territorio = $(auxObj).attr("value");
		
    aux_name = 'hab_input_id_' + cod_dep_terr;
    pobla_benef = $('#' + aux_name);
    v_pobla_benef = $(pobla_benef).attr("value");
    v_pobla_benef = quita_comas(v_pobla_benef);

    if ((iilg_porce == '') || (iilg_porce <= 0) || (iilg_porce >100))
    {
        alert("El porcentaje debe ser mayor a cero y menor o igual a 100 y la población beneficiada menor o igual a la población total");
        return;
    }		
			
    if (cod_territorio == '')
    {
        alert("Debe seleccionar un departamento o municipio");
        return;
    }					
		
    param="cod_territorio=" + 	cod_territorio + "&porcentaje_cobertura=" + iilg_porce + "&poblacion_beneficiada=" + v_pobla_benef;

    datos = ajax_json_init_s("territorio_graba", param);
    if (datos[0].msg == '1')
    {
        local_geogr_ajax();
    }
    else
    {
        alert ("Ocurrió un Error :" + datos[0].detail);
    }		
		
}
	
function edita_depar(id_div_porce_depar)
{
    var x;
    x = document.getElementById(id_div_porce_depar);
    div_hab = $(x).attr("div_hab");

    valorAnt = $(x).html();
    porcentaje = $(x).attr("porcentaje");
    poblacion_total = $(x).attr("poblacion_total");
    poblacion_total = quita_comas(poblacion_total);
    territorio = $(x).attr("cod_territorio");
    var auxNom_por = id_div_porce_depar + "_in";
    var auxNom_next = id_div_porce_depar + "_in_h";				
    id_input_hab = id_div_porce_depar + "_in_h";
    auxHtml = "<input class='input_dep' id='" + auxNom_por + "' name='"+ auxNom_por + "' type='textbox' value='" + porcentaje + "'" + 
    " size='7' maxlength='7' valorAnt='" + valorAnt + "'" + " nro_hab_tot= \"" + poblacion_total + "\"" + " id_input_hab=\"" + id_input_hab + "\"" +
    "  territorio=\"" + cod_territorio + "\" " + " onkeypress=\"return applyMask(this.name,'3',',','.',4,event,2);\" onkeyup=\"edicion_calcula_hab_benef(this);return setFocus(event,'" + auxNom_next+ "')\"> %";		
    $(x).html(auxHtml);		
		
//    auxEdDiv = $(x).attr("ed_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:grabaEdiDep(\'' + id_div_porce_depar + '\')" > <img src="/images/floppy.png" border="0" id="{DES_IMG}"> </a>');
//		
//    auxEdDiv = $(x).attr("eli_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:canceEditDep(\'' + id_div_porce_depar + '\')" > <img src="/images/cancelar.png" border="0" id="{DES_IMG}"> </a>');
		
    //Para el control input de habitantes
		
    y = document.getElementById(div_hab);
    valorAnt = $(y).html();
    poblacion = $(y).attr("poblacion");
    var auxNom = id_div_porce_depar + "_in_h";
    var auxNom_next = id_div_porce_depar + "_grabar";		
    auxHtml = "<input class='input_dep' id='" + auxNom + "' name='" + auxNom + "' type='textbox' value='" + poblacion + 
    "' size='10' maxlength='10' valorAnt='" + valorAnt + "'" + " nro_hab_tot= \"" + poblacion_total + "\"" + " id_input_por=\""+  auxNom_por + 
    "\" onkeypress=\"return applyMask(this.name,'3',',','.',4,event,0);\" onkeyup=\"edicion_calcula_porcentaje(this); return setFocus(event,'" + 
    auxNom_next + "');\"> Habs.";
    $(y).html(auxHtml);
		
    $("#"+id_div_porce_depar+"_edita").hide();
    $("#"+id_div_porce_depar+"_elim").hide();
    
    $("#"+id_div_porce_depar+"_grabar").show();
    $("#"+id_div_porce_depar+"_cancel").show();
    
                  
                
//    auxEdDiv = $(y).attr("ed_div");
//    z = document.getElementById(auxEdDiv);
//    $(z).html('<a href="javascript:grabaEdiDep(\'' + id_div_porce_depar + '\')" id="' + auxNom_next + '" name="' + auxNom_next + 
//        '"> <img src="/images/floppy.png" border="0" id="{DES_IMG}"> </a>');
//				
//    auxEdDiv = $(y).attr("eli_div");
//    z = document.getElementById(auxEdDiv);
//    $(z).html('<a href="javascript:canceEditDep(\'' + id_div_porce_depar + '\')" > <img src="/images/cancelar.png" border="0" id="{DES_IMG}"> </a>');		
		
    return;
}
	
function canceEditDep(id_div_porce_depar)
{
    var auxNom = id_div_porce_depar + "_in";	
    y = document.getElementById(auxNom);
    valorAnt=$(y).attr("valorAnt");
		
    var x;
    x = document.getElementById(id_div_porce_depar);
    div_hab = $(x).attr("div_hab");
    $(x).html(valorAnt);
		
//    auxEdDiv = $(x).attr("ed_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:edita_depar(\''+ id_div_porce_depar + '\')" > <img src="/images/editar.gif" border="0" id="{REP_IMG}" title="Editar"></a>');
//		
//    auxEdDiv = $(x).attr("eli_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:elimi_depar(\''+ id_div_porce_depar + '\')" > <img src="/images/delete.png" border="0" id="{REP_IMG}" title="Eliminar"></a>');		

    //Ahora para la poblacion
    var auxNom = id_div_porce_depar + "_in_h";
    y = document.getElementById(auxNom);
    valorAnt=$(y).attr("valorAnt");
		
    var x;
    x = document.getElementById(div_hab);
    $(x).html(valorAnt);
		
//    auxEdDiv = $(x).attr("ed_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:edita_depar(\''+ id_div_porce_depar + '\')" > <img src="/images/editar.gif" border="0" id="{REP_IMG}" title="Editar"></a>');
//		
//    auxEdDiv = $(x).attr("eli_div");
//    y = document.getElementById(auxEdDiv);
//    $(y).html('<a href="javascript:elimi_depar(\''+ id_div_porce_depar + '\')" > <img src="/images/delete.png" border="0" id="{REP_IMG}" title="Eliminar"></a>');

    $("#"+id_div_porce_depar+"_edita").show();
    if($("#"+id_div_porce_depar+"_elim").attr('class')!="no_mostrar"){
        $("#"+id_div_porce_depar+"_elim").show();
    }
    
    
    $("#"+id_div_porce_depar+"_grabar").hide();
    $("#"+id_div_porce_depar+"_cancel").hide();


}
	

function grabaEdiDep(id_div_porce_depar)
{
    x=document.getElementById(id_div_porce_depar);
    cod_territorio = $(x).attr("cod_territorio");
		
    var auxNom = id_div_porce_depar + "_in";	
    y = document.getElementById(auxNom);				
    iilg_porce = $(y).attr("value");
		
    if ((iilg_porce == '') || (iilg_porce <= 0) || (iilg_porce >100))
    {
        alert("Debe ingresar un porcentaje mayor a cero y menor a 100");
        return;
    }			
			
    auxNom = 	id_div_porce_depar + "_in_h";	
    poblacion_input = document.getElementById(auxNom);
    v_poblacion = $(poblacion_input).attr("value");
    poblacion_benef = quita_comas(v_poblacion);
		
    param="cod_territorio=" + 	cod_territorio + "&porcentaje_cobertura=" + iilg_porce + "&poblacion_beneficiada=" + poblacion_benef;
    //alert(param); return;
    datos = ajax_json_init_s("territorio_graba", param);
    if (datos[0].msg == '1')
    {
        local_geogr_ajax();
    }
    else
    {
        alert ("Ocurrió un Error :" + datos[0].detail);
    }			
}
	
function elimi_depar(id_div_porce_depar)
{
    if (confirm("Desea Eliminar el registro?"))
    {
        x=document.getElementById(id_div_porce_depar);
        cod_territorio = $(x).attr("cod_territorio");
        param="cod_territorio=" + 	cod_territorio;		
        datos = ajax_json_init_s("territorio_elimina", param);
        if (datos[0].msg == '1')
        {
            local_geogr_ajax();
        }
        else
        {
            alert ("Ocurrió un Error :" + datos[0].detail);
        }
    }
}
	
function despliega(divName)
{
    divAux = document.getElementById(divName);
    aux = $(divAux).css("display");
    if (aux == "block")
    {
        //alert(aux);
        $(divAux).slideUp();
    } 
    else 
    {
        $(divAux).slideDown();			 	
    }
		
}
		
function local_geogr_ajax(){
    ajax_init("localizacionGeografica_vista", "detalle_inversion", "","");
}		

inicializaEventos();
	
function getDatosTerritorio(obj)
{
    objCmb = $('#' + obj.id);
    cod_territorio = objCmb.attr("value");
    id_aux = objCmb.attr("territorio");
    param = "cod_territorio=" + cod_territorio;
    //alert (param); return;
    datos = ajax_json_init_s("territorioDatos", param);
			
    nameAuxDiv = "sel_" + id_aux + "_nh";
    div_aux = $('#' + nameAuxDiv);
    div_aux.html(datos.total + ' Habs.');
						
    nameAuxDiv = "sel_" + id_aux + "_h";
    div_aux = $('#' + nameAuxDiv);
    div_aux.html(datos.hombres);
			
    nameAuxDiv = "sel_" + id_aux + "_m";
    div_aux = $('#' + nameAuxDiv);
    div_aux.html(datos.mujeres);
			
    nameAuxInput = "hab_input_id_" + id_aux;
    auxInput = document.getElementById(nameAuxInput);
    auxInput.setAttribute("nro_hab_tot", datos.total);
			
    nameAuxInput = "por_input_id_" + id_aux;
    auxInput = document.getElementById(nameAuxInput);
    auxInput.setAttribute("nro_hab_tot", datos.total);			
}
		
function calcula_porcentaje(obj)
{
    valor = quita_comas(obj.value);
    if (valor == "") valor=0;
    obj_hab = $('#' + obj.id);
    habitantes = obj_hab.attr("nro_hab_tot");
    habitantes = quita_comas(habitantes);
    territorio = obj_hab.attr("territorio");
    //alert(valor + ' ' + habitantes);
    porcentaje = (parseInt(valor) * 100)/parseInt(habitantes);
    aux_name = 'por_input_id_' + territorio;
			
    por_input_id = document.getElementById(aux_name);						
    por_input_id.value = redondeo2decimales(porcentaje);

    return;
}
		
function calcula_hab_benef(obj)
{
    valor = quita_comas(obj.value);		
    if (valor == "") valor=0;			
    obj_por = $('#' + obj.id);
    habitantes = obj_por.attr("nro_hab_tot");
    habitantes = quita_comas(habitantes);
    territorio = obj_por.attr("territorio");			

    hab_benef = (parseFloat(valor) * habitantes)/100;
    //alert(hab_benef);			
    aux_name = 'hab_input_id_' + territorio;
			
    hab_input_id = document.getElementById(aux_name);

    hab_benef = Math.round(hab_benef);				
    hab_input_id.value = formatNumber(hab_benef);

    return;			
			
}
		
function edicion_calcula_hab_benef(obj)
{
    valor = quita_comas(obj.value);		
    if (valor == "") valor=0;			
    obj_por = $('#' + obj.id);
    habitantes = obj_por.attr("nro_hab_tot");
    habitantes = quita_comas(habitantes);
    aux_name = obj_por.attr("id_input_hab");

    hab_benef = (parseFloat(valor) * habitantes)/100;
		
    hab_input_id = document.getElementById(aux_name);

    hab_benef = Math.round(hab_benef);				
    hab_input_id.value = formatNumber(hab_benef);

    return;			
			
}		
		
function edicion_calcula_porcentaje(obj)
{
    valor = quita_comas(obj.value);			
    if (valor == "") valor=0;
    obj_hab = $('#' + obj.id);
    habitantes = obj_hab.attr("nro_hab_tot");
    habitantes = quita_comas(habitantes);

    aux_name = obj_hab.attr("id_input_por");			
    //alert(valor + ' ' + habitantes);
    porcentaje = (parseInt(valor) * 100)/parseInt(habitantes);
			
    por_input_id = document.getElementById(aux_name);						
    por_input_id.value = redondeo2decimales(porcentaje);

    return;
}		