    /*------------ Costo Unitario ---------------*/
    function costo_unitario()
    { 
        a = parseFloat($('[name="met"]').val()); //// Meta
        b = parseFloat($('[name="costo"]').val()); //// Costo
        if (a!=0 && a>0 )
        {
            $('[name="cost_uni"]').val((b/a).toFixed(2) );
        }
    }
    suma=function(f)
        {
            var total=0;
           // alert(f.length)
            for(var x=0;x<f.length;x++){//recorremos los campos dentro del form
                if(f[x].name.indexOf('m1')!=-1)
                {
                     total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m2')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m3')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m4')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m5')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m6')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m7')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m8')!=-1)
                {
                    total+=Number(f[x].value);
                }
                if(f[x].name.indexOf('m9')!=-1)
                {
                    total+=Number(f[x].value);
                }
            }
           //alert(f.length);
           total=total+Number(document.formulario.lb.value);
            if (total - Math.floor(total) != 0) {
                total = total.toFixed(2)
            }
            document.getElementById('total').value=total;//al final colocamos la suma en algún input.  
        }

        function valida_envia()
        { 
                if (document.formulario.act.value.length==0) /////// Objetivo
                  { 
                      alert("Registre el campo ACTIVIDAD") 
                      document.formulario.act.focus() 
                      return 0; 
                  }

                  if (document.formulario.tipo_i.value=="") /////// Tipo de indicadores
                  { 
                      alert("Seleccione el campo TIPO DE INDICADORES") 
                      document.formulario.tipo_i.focus() 
                      return 0; 
                  }

                  if (document.formulario.lb.value=='') /////// Linea Base
                  { 
                      alert("Registre el campo LINEA BASE") 
                      document.formulario.lb.focus() 
                      return 0; 
                  }

                  if (document.formulario.met.value=='') /////// Meta
                  { 
                      alert("Registre el campo META") 
                      document.formulario.met.focus() 
                      return 0; 
                  }

                if (document.formulario.costo.value=='') /////// Costo
                { 
                    alert("Registre el campo COSTO")  
                    document.formulario.costo.focus() 
                    return 0; 
                }


                if (document.formulario.f_ini.value=="") /////// Fecha de Inicio de actividad
                { 
                    alert("Seleccione FECHA DE INICIO DE ACTIVIDAD") 
                    document.formulario.f_ini.focus() 
                    return 0; 
                }

                if (document.formulario.f_final.value=="") /////// Fecha de Conclusion de actividad
                { 
                  alert("Seleccione FECHA FINAL DE ACTIVIDAD") 
                  document.formulario.f_final.focus() 
                  return 0; 
                }
                var fecha_inicial = document.formulario.f_ini.value.split("/")  //fecha inicial
                var fecha_final = document.formulario.f_final.value.split("/")  /*fecha final*/

                if(parseInt(fecha_final[2])<parseInt(fecha_inicial[2]))
                {
                    alert('Error!!  en las Fechas, verifique las gestiones del proyecto')
                    document.formulario.f_final.focus() 
                    return 0;
                }

                 /*------------------------- VERIFICANDO FECHAS ----------------------*/
                if(parseInt(fecha_inicial[2])>parseInt(fecha_final[2]))
                {
                    alert('Error!!  la fecha Inicial no Puede ser posterior a la fecha Final, verifique las fechas')
                    document.formulario.f_ini.focus() 
                    return 0;
                }

                if(parseInt(fecha_final[2])<parseInt(fecha_inicial[2]))
                {
                    alert('Error!!  la fecha Final no Puede ser anterior a la fecha Inicial, verifique las fechas')
                    document.formulario.f_ini.focus() 
                    return 0;
                }
                
                 /*------------------------- Fecha Inicial ----------------------*/
                if(parseInt(fecha_inicial[0])>'31' || parseInt(fecha_inicial[1])>'12' || parseInt(fecha_inicial[2])<'2008')
                {
                    alert('Error!!  Verifique la fecha Inicial')
                    document.formulario.f_ini.focus() 
                    return 0;
                }

                /*------------------------------ Fecha Final ----------------------*/
                if(parseInt(fecha_final[2])>'2027' || parseInt(fecha_final[0])>'31' || parseInt(fecha_final[1])>'12')
                {
                    alert('Error!!  Verifique la fecha Final')
                    document.formulario.f_final.focus() 
                    return 0;
                }

                if(document.formulario.act_reg.value!=0)
                {
                    if (document.formulario.act_dep.value=="") /////// dependencia
                      { 
                          alert("Seleccione la dependencia de la actividad") 
                          document.formulario.act_dep.focus() 
                          return 0; 
                      }
                }
            
            if(parseFloat(document.formulario.met.value)==parseFloat(document.formulario.total.value))
            {
                var OK = confirm(" GUARDAR ACTIVIDAD ?");
                if (OK) {
                        document.formulario.submit();
                        document.getElementById("btsubmit").value = "GUARDARDANDO ACTIVIDAD...";
                        document.getElementById("btsubmit").disabled = true;
                        return true;
                } 
            }
            else
            {
                if(parseFloat(document.formulario.met.value)>parseFloat(document.formulario.total.value))
                {
                    alert('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MENOR A LA META DE LA ACTIVIDAD')
                    document.formulario.met.focus() 
                    return 0; 
                }
                else
                {
                    alert('ERROR!! LA SUMA DE LO PROGRAMADO NO PUEDE SER MAYOR A LA META DE LA ACTIVIDAD')
                    document.formulario.met.focus() 
                    return 0; 
                }
            }       
        }