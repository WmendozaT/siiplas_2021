// Popup code
var gPopupMask = null;
var gPopupContainer = null;
var gPopFrame = null;
var gReturnFunc;
var gPopupIsShown = false;

var gHideSelects = false;


var gTabIndexes = new Array();
// Pre-defined list of tags we want to disable/enable tabbing into
var gTabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");

// If using Mozilla or Firefox, use Tab-key trap.
//if (!document.all) {
//	document.onkeypress = keyDownHandler;
//}

/*Para AJAX de JQUERY*/
jQuery(document).ajaxStart(function(){
    showPopWinMod(150, 60, null);
}).ajaxStop(function() {
    window.top.hidePopWinMod();
});

function myEmptyCallBack() {
	return;
}

function get_xmlhttp() {
        try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                        xmlhttp = false;
                }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
                xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
}

function isdefined( variable) {
    return (typeof(window[variable]) == "undefined")?  false: true;
}

/* ajax_json_init
 * Hace una peticion a una pagina que devuelve una variable con formato json.
 * @version 1.0
 * @package ajax
 * @param string ajax_server  url de la pagina servidor
 * @param string values   variables pasadas por url. Ej. variable=valor&otravariable=suvalor
 */
 function ajax_json_init(ajax_server,values,callback)
 {
		var objetus;
    objetus = get_xmlhttp(); 
    objetus.onreadystatechange = function() {   	
    	if ( objetus.readyState == 4 )
    	{
           var resultado = eval("(" + objetus.responseText + ")");
           if ( callback != '')
           {
             callback(resultado);
            }
             /*
           if ( (callback != '') && (valuesCall!="") )
             callback(resultado, valuesCall);                 					 		
             */
    		}   		
   		}
   	if (values != "")
		    objetus.open ("GET", ajax_server + "?" + values, true);   		    
		else 
				objetus.open ("GET", ajax_server, true);   		    
		objetus.send(null); 
 	}

/* ajax_json_init
 * Hace una peticion a una pagina que devuelve una variable con formato json.
 * @version 1.0
 * @package ajax
 * @param string ajax_server  url de la pagina servidor
 * @param string values   variables pasadas por url. Ej. variable=valor&otravariable=suvalor
 */
 function ajax_json_init_s(ajax_server,values)
 {
		var objetus;
		//var resultado;
    objetus = get_xmlhttp();	
   	if (values != "")
		    objetus.open ("GET", ajax_server + "?" + values, false);   		    
		else {
				objetus.open ("GET", ajax_server, false);   		    
			}
		objetus.send(null);
		var resultado = eval("(" + objetus.responseText + ")");
		return resultado;
 	}

/*function ajax_init( ajax_server, div_container, values, callback )
{
	var objetus;

    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, true);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {      	
					showPopWinMod(230, 48, null);
        }
        else if ( objetus.readyState==4)
        {
        	window.top.hidePopWinMod();
            if( objetus.status==200)
            {		
                document.getElementById(div_container).innerHTML = objetus.responseText;
                if ( callback != '' )
                  callback();
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}*/

function ajax_nowait( ajax_server, div_container, values, callback )
{
	var objetus;

    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, false);

    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
            document.getElementById(div_container).style.display = "";
            document.getElementById(div_container).innerHTML = "...";

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
                document.getElementById(div_container).innerHTML = objetus.responseText;
                if ( callback != '' )
                  callback();
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}


function ajax_empty( div_container )
{
  if (document.getElementById(div_container) ) {
    document.getElementById(div_container).style.display = "";
    document.getElementById(div_container).innerHTML = "";
  }
}

function iframe_get_xmlhttp() {
  try {
    xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

String.prototype.trim= function() {//Agrega la función trim al objeto String
   return this.replace(/(^\s*)|(\s*$)/g,""); //elimina espacios a izquierda y derecha
   }

function ajaxMonto( ajax_server, div_container, values, callback)
{
	var objetus;

    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, true);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
        	document.getElementById(div_container).value = '---------';
        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {    					
								var monto = new String(objetus.responseText);
                 document.getElementById(div_container).value = monto.trim();
                if ( callback != '' )
                  eval(callback);
            }
        }
    }
    objetus.send(null);
}

function ajax_msg( ajax_server, values, callback, msg)
{
	var objetus;
	var sw;
  objetus = get_xmlhttp();
  objetus.open ("GET", ajax_server + "?" + values, true);
  
  objetus.onreadystatechange=function() 
  {
    if ( objetus.readyState==4)
    {
      if( objetus.status==200)
      {
    		sw = objetus.responseText;
				if (eval(sw)==0)
				{
					//eval(callback);
				}
				else
				{
					alert(msg);
				}	
      }
      else
      {
				window.alert('error-['+ objetus.status +']-' + objetus.responseText );
      }
    }
  }
  objetus.send(null);
}

function ajax_init( ajax_server, div_container, values, callback ){
  $("#"+div_container).load(ajax_server,values,callback);
}

//function ajax_init( ajax_server, div_container, values, callback )
//{
//	var objetus;
//
//    objetus = get_xmlhttp();
//    objetus.open ("GET", ajax_server + "?" + values, true);
//    objetus.onreadystatechange=function() {
//        if ( objetus.readyState == 1 )
//        {      	
//        	/*-----------------------------------------*/
//        	/*---------------Modificado----------------*/
//   				/*-----------------------------------------*/
//					showPopWinMod(150, 60, null);
//
//        	/*-------------------------------------*/
//        }
//        else if ( objetus.readyState==4)
//        {
//        	window.top.hidePopWinMod();
//            if( objetus.status==200)
//            {		
//            	document.getElementById(div_container).innerHTML = objetus.responseText;
//	              if ( callback != '' )
//	                callback();
//            }
//            else
//            {
//                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
//            }
//        }
//    }
//    objetus.send(null);
//}
					
function ajax_init2( ajax_server, div_container, values, callback )
{
	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, true);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {      	
			showPopWinMod(230, 48, null);
        }
        else if ( objetus.readyState==4)
        {
        	//window.top.hidePopWinMod(); // 
            if( objetus.status==200)
            {
            	document.getElementById(div_container).innerHTML = objetus.responseText;
	              if ( callback != '' )
	                callback();
            }
            else if (objetus.status==404)
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}

function include_js_ajax(path){
    $.getScript(path);
}

//function include_js_ajax(path){
//        var j = document.createElement("script");
//        j.type = "text/javascript";
//        j.src = path;
//        document.body.appendChild(j);
//}
