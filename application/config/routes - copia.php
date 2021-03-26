<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "user";
$route['404_override'] = '';

$route['admin/logout'] = 'user/logout';
$route['admin/validate'] = 'user/validate_credentials';
$route['admin/dashboard'] = 'user/dashboard_index';
$route['admin/dm/(:any)'] = 'user/dashboard_menu/$1';

/*PROGRAMACION*/
$route['admin/combo_ubicacion'] = 'user/combo_ubicacion';
$route['admin/combo_fase_etapas'] = 'user/combo_fases_etapas';
$route['admin/combo_clasificador'] = 'user/combo_clasificador';


/*-------------------------PROGRAMACION ESTRATEGICA-----------------------------*/
$route['admin/pei/mision'] = 'user/mision';		///// Mision Institucional
$route['admin/pei/mision/accion/(:any)'] = 'user/pei_accion/$1';	//// Pei Accion
$route['admin/pei/vision'] = 'user/vision';		///// Vision Institucional
$route['admin/pei/ayuda/acerca'] = 'user/acerca';	//// Ayuda

/* MARCO ESTRATEGICO */
/*--------------------------------------- Objetivos Estrategicos ----------------------------------------------*/
$route['me/objetivos_estrategicos'] = 'mestrategico/cobjetivos_estrategico/objetivos_estrategicos';	//// Lista Objetivos Estrategicos
$route['me/valida_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/valida_objetivos_estrategicos';	//// Valida Objetivos Estrategicos
$route['me/get_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/get_objetivos_estrategicos';	//// get Objetivos Estrategicos
$route['me/update_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/update_objetivos_estrategicos';	//// Valida Objetivos Estrategicos
$route['me/delete_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/delete_objetivos_estrategicos';	//// Delete Objetivos Estrategicos

/*--------------------------------------- Acciones Estrategicas ----------------------------------------------*/
$route['me/acciones_estrategicas/(:any)'] = 'mestrategico/cacciones_estrategicas/acciones_estrategicas/$1';	//// Lista Acciones Estrategicas
$route['me/valida_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/valida_acciones_estrategicas';	//// Valida Acciones Estrategicos
$route['me/get_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/get_acciones_estrategicas';	//// get Acciones Estrategicas
$route['me/update_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/update_acciones_estrategicas';	//// Valida Update Acciones Estrategicas
$route['me/delete_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/delete_acciones_estrategicas';	//// Delete Acciones Estrategicos

/*--------------------------------------- Resultado de Mediano Plazo ----------------------------------------------*/
$route['admin/me/combo_fun_uni'] = 'mestrategico/cresultado_mplazo/combo_funcionario_unidad_organizacional'; ////// Combo Responsable a Unidad Organizacional
$route['me/resultados_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/list_resultado_mediano_plazo/$1';	//// Lista Resultados de Mediano Plazo
$route['me/new_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/new_resultado_mediano_plazo/$1';	//// Nuevo Resultado de Mediano Plazo
$route['me/valida_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/valida_resultado_mediano_plazo';	//// Valida Resultado de Mediano Plazo
$route['me/update_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/update_resultado_mediano_plazo/$1';	//// Editar Resultado de Mediano Plazo
$route['me/valida_update_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/valida_update_resultado_mediano_plazo';	//// Valida Update Resultado de Mediano Plazo
$route['me/rep_rmplazo/(:any)'] = 'mestrategico/cresultado_mplazo/reporte_resultado_mediano_plazo/$1';	//// Reporte Resultado de Mediano Plazo
$route['me/delete_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/delete_resultado_mplazo';	//// Delete Resultado de Mediano Plazo

/*-------------------------------------- Productos Terminales -----------------------------------------------*/
$route['me/pterminales_mp/(:any)'] = 'mestrategico/cpterminal/list_pterminal_mp/$1';	//// Lista Productos Terminales
$route['me/new_ptmplazo/(:any)'] = 'mestrategico/cpterminal/new_pterminal_mplazo/$1';	//// Nuevo Resultado de Mediano Plazo
$route['me/valida_pterminal_mplazo'] = 'mestrategico/cpterminal/valida_pterminal_mediano_plazo';	//// Valida Resultado de Mediano Plazo
$route['me/update_pterminal_mp/(:any)'] = 'mestrategico/cpterminal/update_pterminal_mediano_plazo/$1';	//// Editar Resultado de Mediano Plazo
$route['me/valida_update_pterminal_mp'] = 'mestrategico/cpterminal/valida_update_pterminal_mediano_plazo';	//// Valida Update Resultado de Mediano Plazo
$route['me/rep_pterminal_mp/(:any)'] = 'mestrategico/cpterminal/reporte_pterminal_mediano_plazo/$1';	//// Reporte Productos Terminales Mediano Plazo

/* ASIGNACION DE ACCIONES DE MEDIANO PLAZO A CARPETA POA  */
$route['mnt/red_o'] = 'mantenimiento/mantenimiento_cpoa/red_objetivos';
$route['mnt/asignar/(:any)'] = 'mantenimiento/mantenimiento_cpoa/asignar_obj_poa/$1'; //// Lista para Asignar Accion a Carpeta POA
$route['mnt/asignar_accion'] = 'mantenimiento/mantenimiento_cpoa/asignar_accion'; //// Asignar Accion a Carpeta POA
$route['mnt/quitar_accion'] = 'mantenimiento/mantenimiento_cpoa/quitar_accion'; //// Quitar Accion de Carpeta POA

/* RED DE ACCIONES */
$route['prog/redobj'] = 'mantenimiento/mantenimiento_cpoa/red_acciones'; //// lista de Objetivos Padres
$route['prog/obj/(:any)'] = 'analisis_situacion/red_objetivos/list_acciones_mediano_plazo/$1';///// Lista de Resultados de Mediano Plazo

/*----------------------------------- Resultados de Corto plazo -----------------------------------------*/
$route['prog/resultado_cplazo/(:any)'] = 'analisis_situacion/cresultado_cplazo/lista_resultados_corto_plazo/$1/$2';//Lista de Resultados de Corto Plazo
$route['prog/reporte_resultado_cp/(:any)'] = 'analisis_situacion/cresultado_cplazo/reporte_resultados_corto_plazo/$1/$2';//Reporte Lista de Resultados de Corto Plazo
$route['prog/temporalidad_cplazo/(:any)'] = 'analisis_situacion/cresultado_cplazo/rcplazo_temporalidad/$1/$2';//// Programar Temporalidad Mensual
$route['prog/valida_temporalidad_cplazo'] = 'analisis_situacion/cresultado_cplazo/valida_rcplazo_temporalidad';//// Valida Temporalidad Mensual

/*----------------------------------- Producto Terminal de Corto plazo -----------------------------------------*/
$route['prog/pterminal_cp/(:any)'] = 'analisis_situacion/cpterminal_cplazo/list_pterminal_cp/$1/$2';//Lista de Productos Terminal de Corto Plazo
$route['prog/reporte_pterminal_cp/(:any)'] = 'analisis_situacion/cpterminal_cplazo/reporte_pterminal_corto_plazo/$1/$2';//Reporte Lista de Resultados de Corto Plazo
$route['prog/temporalidad_pt_cplazo/(:any)'] = 'analisis_situacion/cpterminal_cplazo/ptcplazo_temporalidad/$1/$2';//// Programar Temporalidad Mensual
$route['prog/valida_temporalidad_ptcplazo'] = 'analisis_situacion/cpterminal_cplazo/valida_ptcplazo_temporalidad';//// Valida Temporalidad Mensual



/*--------------------------------------- Objetivos Estrategicos ANTIGO A ELIMINAR ---------------------------------------------------*/
$route['prog/rcp_form1/(:any)'] = 'analisis_situacion/red_objetivos/nuevo_resultado_cp/$1/$2';//´nuevo Resultado de Corto Plazo form1
$route['prog/rcp_guardar'] = 'analisis_situacion/red_objetivos/valida_add_resultado_cp';// Valida Resultado de corto plazo
$route['prog/rcp_form2/(:any)'] = 'analisis_situacion/red_objetivos/form2_resultado_cp/$1/$2/$3/$4';//´nuevo Resultado de Corto Plazo form2
$route['prog/rcp_guardar2'] = 'analisis_situacion/red_objetivos/valida_add2_resultado_cp';// Valida Resultado de corto plazo Form2

$route['prog/rcp_mod1/(:any)'] = 'analisis_situacion/red_objetivos/modificar_resultado_cp/$1/$2/$3';//Modificar Resultado de Corto Plazo form1
$route['prog/rcp_update'] = 'analisis_situacion/red_objetivos/valida_update_resultado_cp';// Valida Resultado de corto plazo
$route['admin/prog/rcp_delete'] = 'analisis_situacion/red_objetivos/delete_resultado_cp'; ////// Eliminar Resultado corto plazo
/*--- Indicador Corto Plazo*/
$route['prog/rcp_new_indicador/(:any)'] = 'analisis_situacion/red_objetivos/form_indicador_cp/$1/$2/$3/$4';// Nuevo Indicador
$route['prog/rcp_add_indicador'] = 'analisis_situacion/red_objetivos/valida_add_indicador_cp';// Valida Indicador

$route['prog/rcp_update_indicador/(:any)'] = 'analisis_situacion/red_objetivos/update_indicador_cp/$1/$2/$3/$4/$5';// Editar Indicador
$route['prog/rcp_update_indicador'] = 'analisis_situacion/red_objetivos/valida_update_indicador_cp';// Valida Update Indicador
$route['admin/prog/rcp_delete_ind'] = 'analisis_situacion/red_objetivos/delete_indicador_cp'; ////// Eliminar Indicador

$route['admin/prog/reporte_resultado_cp/(:any)'] = 'analisis_situacion/red_objetivos/reporte_accion_cp/$1/$2'; ////// Reporte Acciones de Corto Plazo

/*---------------- producto Terminal ---------------------*/
$route['prog/pterminal/(:any)'] = 'programacion/producto_terminal/lista_pterminal/$1/$2/$3';	/// LISTA DE PRODUCTOS TERMINALES
$route['prog/pt_nuevo/(:any)'] = 'programacion/producto_terminal/nuevo_pterminal/$1/$2/$3';	//NUEVO PRODUCTO TERMINAL
$route['prog/pt_guardar'] = 'programacion/producto_terminal/guardar_pterminal';	//GUARDAR PRODUCTO TERMINAL
$route['prog/pt_mod/(:any)'] = 'programacion/producto_terminal/form_mod_pterminal/$1/$2/$3/$4';	//VISTA MODIFICAR PRODUCTO TERMINAL
$route['prog/pt_update'] = 'programacion/producto_terminal/modificar_pterminal';	//MODIFICAR PRODUCTO TERMINAL
$route['prog/pt_delete'] = 'programacion/producto_terminal/eliminar_pterminal';	//ELIMINAR PRODUCTO TERMINAL
$route['prog/pt_reporte/(:any)'] = 'programacion/producto_terminal/reporte_pterminal/$1/$2/$3';	/// REPORTE DE PRODUCTOS TERMINALES




/*---------------------------------- PDF --------------------------------------*/
$route['admin/me/pdf'] = 'objetivos/b_pdf';
$route['admin/me/ficha_tecnica'] = 'reportes/reporte/ficha_tecnica';
//////////////////////////////////MANTENIMIENTO   2   /////////////////////////////////////////
//APERTURA PROGRAMATICA PADRES
$route['mnt/prog_p'] = 'mantenimiento/capertura_programatica/main_apertura_programatica_padres';
//APERTURA PROGRAMATICA HIJOS
$route['mnt/aper_prog'] = 'mantenimiento/capertura_programatica/main_apertura_programatica';
$route['mnt/report_aper_prog'] = 'mantenimiento/capertura_programatica/reporte_apertura_programatica'; ///// Reporte
//analasis ver si habra abm de las aperturas
$route['admin/mantenimiento/add_aper'] = 'cmantenimiento/add_aper';
$route['admin/mantenimiento/get_aper'] = 'cmantenimiento/get_aper';
$route['admin/mantenimiento/del_aper'] = 'cmantenimiento/del_aper';
//POA
$route['mnt/poa'] = 'mantenimiento/cpoa';

//--- DIRECTO - PROGRAMACION DE INSUMOS trabajando/vista
$route['prog/ins/(:any)'] = 'programacion/cprog_insumos/insumos/$1/$2';//--
$route['prog/delete_ins_total/(:any)'] = 'programacion/cprog_insumos/delete_insumo_total/$1';///// Eliminar Insumos total

//--- DELEGADO - PROGRAMACION DE INSUMOS/COMPONENTES Wilmer (Nuevo)
$route['prog/ins_com/(:any)'] = 'insumos/cprog_insumos_delegado/prog_isumos_com/$1/$2/';//PROGRAMACION DE INSUMOS A NIVEL COMPONENTE LISTA -w
$route['prog/combo_partidas'] = 'insumos/cprog_insumos_delegado/combo_partidas_hijos';// COMBO PARTIDAS -w
$route['prog/nuevo_ins_c/(:any)'] = 'insumos/cprog_insumos_delegado/nuevo_insumo/$1/$2/$3';//NUEVO INSUMOS A NIVEL COMPONENTE -w 
$route['prog/ins_c_prog/(:any)'] = 'insumos/cprog_insumos_delegado/insumo_programado/$1/$2/$3/$4';//NUEVO INSUMOS A NIVEL COMPONENTE PROGRAMADO -w

$route['prog/mod_ins_com/(:any)'] = 'insumos/cprog_insumos_delegado/mod_insumo/$1/$2/$3/$4';//MODIFICAR INSUMOS A NIVEL COMPONENTE -w
$route['prog/del_ins_com/(:any)'] = 'insumos/cprog_insumos_delegado/del_insumo/$1/$2/$3';//ELIMINAR INSUMOS A NIVEL COMPONENTE -w
$route['prog/verif_ptto_gestion'] = 'insumos/cprog_insumos_delegado/verif_ptto_asignado'; ////// verificando Ptto Asignado por Gestiones
$route['prog/delete_ins_com/(:any)'] = 'insumos/cprog_insumos_delegado/eliminar_insumos/$1/$2';//ELIMINAR INSUMOS DE LA ACTIVIDAD

//--- DIRECTO - PROGRAMACION DE INSUMOS/ACTIVIDADES Wilmer (Nuevo)
$route['prog/ins_act/(:any)'] = 'insumos/cprog_insumos_directo/prog_isumos_act/$1/$2/$3';//PROGRAMACION DE INSUMOS A NIVEL ACTIVIDADES
$route['prog/nuevo_ins_a/(:any)'] = 'insumos/cprog_insumos_directo/nuevo_insumo/$1/$2/$3/$4';//NUEVO INSUMO
$route['prog/mod_ins_a/(:any)'] = 'insumos/cprog_insumos_directo/mod_insumo/$1/$2/$3/$4/$5/$6';//MODIFICAR INSUMO
$route['prog/ins_a_prog/(:any)'] = 'insumos/cprog_insumos_directo/insumo_programado/$1/$2/$3/$4/$5/$6';//NUEVO INSUMOS A NIVEL COMPONENTE PROGRAMADO -w
$route['prog/del_ins_a/(:any)'] = 'insumos/cprog_insumos_directo/del_insumo/$1/$2/$3/$4/$5';//ELIMINAR INSUMOS A NIVEL ACTIVIDADES
$route['prog/delete_ins_act/(:any)'] = 'insumos/cprog_insumos_directo/eliminar_insumos/$1/$2/$3';//ELIMINAR INSUMOS DE LA ACTIVIDAD

//--- DIRECTO - PROGRAMACION DE INSUMOS/PRODUCTOS Wilmer (Nuevo)
$route['prog/ins_prod/(:any)'] = 'insumos/cprog_insumos_directo_p/prog_isumos_prod/$1/$2/$3';//PROGRAMACION DE INSUMOS A NIVEL PRODUCTOS
$route['prog/nuevo_ins_p/(:any)'] = 'insumos/cprog_insumos_directo_p/nuevo_insumo/$1/$2/$3/$4';//NUEVO INSUMO A NIVEL DE PRODUCTO
$route['prog/mod_ins_p/(:any)'] = 'insumos/cprog_insumos_directo_p/mod_insumo/$1/$2/$3/$4/$5/$6';//MODIFICAR INSUMO A NIVEL PRODUCTO
$route['prog/del_ins_p/(:any)'] = 'insumos/cprog_insumos_directo_p/del_insumo/$1/$2/$3/$4/$5';//ELIMINAR INSUMOS A NIVEL PRODUCTO
$route['prog/ins_p_prog/(:any)'] = 'insumos/cprog_insumos_directo_p/insumo_programado/$1/$2/$3/$4/$5/$6';//NUEVO INSUMOS A NIVEL COMPONENTE PROGRAMADO -w
$route['prog/delete_ins_p/(:any)'] = 'insumos/cprog_insumos_directo_p/eliminar_insumos/$1/$2/$3';//ELIMINAR INSUMOS DE LA ACTIVIDAD

//--- PONDERACION
$route['prog/pond_o'] = 'programacion/cponderacion_ogestion/lista_red_objetivos_o';//objetivo de gestion - vista de red de objetivos para la ponderacion
$route['prog/pond_ogestion/(:any)'] = 'programacion/cponderacion_ogestion/lista_ogestion/$1';//objetivo de gestion pondracion
$route['prog/pond_pt'] = 'programacion/cponderacion_pterminal/lista_red_objetivos_pt';//producto terminal- vista de red de objetivos para la ponderacion
$route['prog/list_ogestion/(:any)'] = 'programacion/cponderacion_pterminal/lista_ogestion/$1';//lista de objetivo de gestion - producto terminal
$route['prog/pond_pterminal/(:any)'] = 'programacion/cponderacion_pterminal/lista_pterminal/$1/$2';//lista de objetivo de gestion - producto terminal
$route['prog/pond_prog'] = 'programacion/cponderacion_programas/ponderacion_programas';//objetivo de gestion - vista de red de objetivos para la ponderacion
$route['prog/pond_p'] = 'programacion/cponderacion_proyecto/lista_red_objetivos_p';//lista de programas para la ponderacion del proyecto
$route['prog/pond_proy/(:any)'] = 'programacion/cponderacion_proyecto/ponderacion_proyectos/$1';//lista de proyectos
//=========================================FIN DE PROGRAMACION ==============================================

//===================== REGISTRO DE EJECUCION
//--- REGISTRO EJECUCION POA
$route['reg/ejec_op'] = 'registro/cejec_ogestion_pterminal/lista_red_programas';
$route['reg/lo/(:any)'] = 'registro/cejec_ogestion_pterminal/lista_ogestion_pterminal/$1/$2';
$route['reg/add_ejec_op'] = 'registro/cejec_ogestion_pterminal/add_ejec_op';
$route['reg/lm/(:any)'] = 'registro/cejec_ogestion_pterminal/mostrar_ejec_programa/$1/$2';
$route['reg/lmes/(:any)'] = 'registro/cejec_ogestion_pterminal/lista_mes_ejec/$1/$2';
$route['reg/ejec_mes/(:any)'] = 'registro/cejec_ogestion_pterminal/ejecucion_mes/$1/$2/$3';
$route['reg/arc/(:any)'] = 'registro/cejec_ogestion_pterminal/lista_archivos/$1/$2';
$route['reg/add_arc'] = 'registro/cejec_ogestion_pterminal/add_arc';
$route['reg/list_arc/(:any)'] = 'registro/cejec_ogestion_pterminal/l_archivos_mes/$1/$2/$3/4';
//registro sigep
$route['reg/l_sigep'] = 'registro/cejec_pres_sigep/lista_proy_ejec';
$route['reg/c_sigep'] = 'registro/cejec_pres_sigep/lista_ejec_pres_csv';
$route['reg/add_sigep'] = 'registro/cejec_pres_sigep/subir_sigep';


//======================== REPORTES---------------------------------------

$route['rep/seg_pt'] = 'reportes/cseg_alerta_pterminal';//MENU SEGUIMIENTO DEL PRODUCTO TERMINAL
$route['rep/nivel_pt/(:any)'] = 'reportes/cseg_alerta_pterminal/lista_ogestion/$1';//lista de objetivo de gestion
$route['rep/lista_pt/(:any)'] = 'reportes/cseg_alerta_pterminal/lista_pterminal/$1/$2';//lista de productos terminales
$route['rep/graf_pt/(:any)'] = 'reportes/cseg_alerta_pterminal/grafico_pterminal/$1/$2/$3';//alerta temprana del producto terminal
$route['rep/nivel_pt_o/(:any)'] = 'reportes/cseg_alerta_pterminal/nivel_ogestion/$1';//nivel objetivo de gestion
$route['rep/graf_oges/(:any)'] = 'reportes/cseg_alerta_pterminal/grafico_nivel_ogestion/$1/$2';//nivel objetivo de gestion
$route['rep/nivel_pt_pr/(:any)'] = 'reportes/cseg_alerta_pterminal/nivel_programa/$1';//nivel programa
$route['rep/inst_pt'] = 'reportes/cseg_alerta_pterminal/nivel_institucion';//nivel institucion



/*============================= MODULO DE REPORTES =============================================*/
$route['admin/rep/at_acciones'] = 'reportes/reporte_w/alerta_temprana_acciones'; //// menu alerta temprara
/*-------------------- Seguimiento Temprana de acciones ----------------------*/
$route['admin/rep/productos'] = 'reportes/reporte_w/productos'; //// productos total por institucion
$route['admin/rep/list_productos'] = 'reportes/reporte_w/programas_productos'; //// productos a nivel programas
$route['admin/rep/list_productos_proy/(:any)'] = 'reportes/reporte_w/proyectos_productos/$1'; //// productos a nivel proyectos

$route['admin/rep/acciones'] = 'reportes/reporte_w/acciones';
$route['admin/rep/list_acciones'] = 'reportes/reporte_w/programas_acciones'; //// acciones a nivel programas
$route['admin/rep/list_acciones_proy/(:any)'] = 'reportes/reporte_w/proyectos_acciones/$1'; //// Acciones a nivel proyectos

/*------------------------------- Resumen de ejecucion fisica y Presupuestaria -------------------------------------*/
$route['admin/rep/rep_refisfin'] = 'reportes/reporte_w/seleccion_reporte_resumen_fis_fin'; //// Seleccion de las Acciones
$route['admin/rep/valida_resfisfin'] = 'reportes/reporte_w/validar_seleccion_resumen_fis_fin'; //// valida seleccion
$route['admin/rep/gerencial_proyectos/(:any)'] = 'reportes/reporte_w/reporte_gerencial_proyectos/$1/$2/$3/$4'; //// a nivel institucional EJECUCION FISICA Y FINANCIERA
$route['admin/rep/ver_proyectos_fis/(:any)'] = 'reportes/reporte_w/ver_reporte_gerencial_fis/$1/$2/$3/$4/$5/$6'; ///// ver los proyectos gerenciales segun su tipo-	EJECUCION FISICA
$route['admin/rep/ver_proyectos_fin/(:any)'] = 'reportes/reporte_w/ver_reporte_gerencial_fin/$1/$2/$3/$4/$5/$6'; ///// ver los proyectos gerenciales segun su tipo-	EJECUCION FINANCIERA

/*------------------------------- Reportes Acciones -------------------------------------*/
$route['admin/rep/list_proy_ok'] = 'reportes/reporte_w/list_proyectos_aprobados';  //// Reporte lista de proyectos aprobados
$route['admin/rep/rep_acciones'] = 'reportes/reporte_w/reporte_excel_acciones'; //// Reporte Resumen ejecucion de acciones
$route['admin/rep/get_accion/(:any)'] = 'reportes/reporte_w/get_ejecuccion_accion/$1'; //// Get Accion Proyecto en ejecucion
$route['admin/rep/excel'] = 'reportes/reporte_w/excel'; //// Reporte excel

/*------------------------------- Evaluacion -------------------------------------*/
$route['admin/rep/evaluacion_acciones'] = 'reportes/reporte_w/evaluacion_acciones'; //// Evaluacion de Acciones
$route['admin/rep/proyectos/(:any)'] = 'reportes/reporte_w/list_proyectos/$1'; //// Evaluacion de Acciones por proyectos
$route['admin/rep/eficacia/(:any)'] = 'reportes/reporte_w/evaluacion_eficacia/$1/$2'; //// Tabal de Eficacia
$route['admin/rep/valida_eficacia'] = 'reportes/reporte_w/valida_evaluacion'; //// valida evaluacion Eficacia
$route['admin/rep/evaluacion/(:any)'] = 'reportes/reporte_w/iframe_evaluacion/$1/$2'; //// Iframe Evaluacion
$route['admin/rep/rep_evaluacion/(:any)'] = 'reportes/reporte_w/evaluacion_accion/$1/$2'; //// Evaluacion Proyecto

/*-----------------------------Reportes - Reporte de programa de inversion publica --------------------------*/
$route['admin/rep/iframe_rep_unidad/(:any)'] = 'reportes/reporte_w/iframe_reporte_por_unidad/$1'; //// iframe reporte de programa por unidad ejecutora
$route['admin/rep/rep_unidad/(:any)'] = 'reportes/reporte_w/reporte_por_unidad/$1'; //// iframe reporte de programa por unidad ejecutora

/*-----------------------------Reportes - Reporte Ejecucion Inversion Publica--------------------------*/
$route['admin/rep/rep_inversion'] = 'reportes/reporte_w/seleccion_reporte_inversion_publica';
$route['admin/rep/valida_seleccion_ue'] = 'reportes/reporte_w/validar_seleccion';
$route['admin/rep/reporte_inversion/(:any)'] = 'reportes/reporte_w/reporte_ejecucion_inversion_publica/$1/$2/$3/$4';
$route['admin/rep/rep_pdf_inversion/(:any)'] = 'reportes/reporte_w/pdf_reporte_ejecucion_inversion_publica/$1/$2/$3/$4';

$route['admin/rep/rep_inversion_unidad/(:any)'] = 'reportes/reporte_w/reporte_ejecucion_inversion_publica_unidad/$1/$2/$3/$4/$5'; ///// reporte por unidad 
$route['admin/rep/reporte_inversion_unidad/(:any)'] = 'reportes/reporte_w/pdf_reporte_ejecucion_inversion_publica_unidad/$1/$2'; ///// reporte por unidad pdf

/*-----------------------------Reportes - Reporte Institucional (Global)--------------------------*/
$route['admin/rep/rep_institucion'] = 'reportes/reporte_institucional/seleccion_reporte_institucional';
$route['admin/rep/valida_seleccion_accion'] = 'reportes/reporte_institucional/validar_seleccion';
$route['admin/rep/mis_acciones/(:any)'] = 'reportes/reporte_institucional/mis_acciones_seleccionadas/$1/$2/$3/$4';

$route['admin/rep/unidad/(:any)'] = 'reportes/reporte_institucional/unidad_ejecutora/$1/$2/$3/$4'; ///// REPORTE UNIDAD
$route['admin/rep/reporte_por_unidad/(:any)'] = 'reportes/reporte_institucional/rep_unidad_ejecutora/$1/$2/$3/$4'; ///// REPORTE PDF UNIDAD

$route['admin/rep/accion_por_accion/(:any)'] = 'reportes/reporte_institucional/proyecto_por_accion/$1/$2/$3/$4/$5'; //// ACCION POR UNIDAD


$route['admin/rep/estado/(:any)'] = 'reportes/reporte_institucional/estado_proyecto/$1/$2/$3/$4'; ///// REPORTE ESTADO DEL PROYECTO
$route['admin/rep/reporte_por_estado/(:any)'] = 'reportes/reporte_institucional/rep_estado_proyecto/$1/$2/$3/$4'; ///// REPORTE PDF ESTADO

//$route['admin/rep/prog/(:any)'] = 'reportes/reporte_institucional/eje_programatico/$1/$2/$3/$4';
//$route['admin/rep/reporte_por_eje/(:any)'] = 'reportes/reporte_institucional/rep_eje_programatica/$1/$2/$3/$4'; ///// REPORTE PDF EJE PROGRAMATICA

$route['admin/rep/region/(:any)'] = 'reportes/reporte_institucional/por_region/$1/$2/$3/$4';
$route['admin/rep/reporte_por_region/(:any)'] = 'reportes/reporte_institucional/rep_por_region/$1/$2/$3/$4'; ///// REPORTE PDF POR REGION

$route['admin/rep/municipio/(:any)'] = 'reportes/reporte_institucional/por_municipio/$1/$2/$3/$4';
$route['admin/rep/reporte_por_municipio/(:any)'] = 'reportes/reporte_institucional/rep_por_municipio/$1/$2/$3/$4'; ///// REPORTE PDF POR MUNICIPIO

$route['admin/rep/provincia/(:any)'] = 'reportes/reporte_institucional/por_provincia/$1/$2/$3/$4';
$route['admin/rep/reporte_por_provincia/(:any)'] = 'reportes/reporte_institucional/rep_por_provincia/$1/$2/$3/$4'; ///// REPORTE PDF POR PROVINCIA

/*-----------------------------Reportes - Reporte por Tipo de Gasto--------------------------*/
$route['admin/rep/rep_tipo_gasto'] = 'reportes/reporte_tipogasto/seleccion_tipo_gasto';
$route['admin/rep/valida_seleccion_tg'] = 'reportes/reporte_tipogasto/validar_seleccion';
$route['admin/rep/mis_acciones_tg/(:any)'] = 'reportes/reporte_tipogasto/mis_acciones_seleccionadas/$1/$2/$3/$4';
$route['admin/rep/reporte_ptto_ejecucion/(:any)'] = 'reportes/reporte_tipogasto/rep_ejecucion_ptto_unidad_ejecutora/$1/$2/$3/$4'; ///// REPORTE PDF RESUMEN DE EJECUCION PRESUPUESTARIA

/*-----------------------------REPORTES SEGUIMIENTO --------------------------*/
/*-------------------------Reportes - Objetivos de Gestion --------------------*/
$route['admin/rep/rep_ogestion'] = 'reportes/reporte_ogestion/objetivos_gestion'; //// Menu Objetivos de Gestion
$route['admin/rep/rep_og_institucion'] = 'reportes/reporte_ogestion/objetivos_gestion_institucional'; ///// a nivel de intitucion
$route['admin/rep/rep_og_institucion_detalles'] = 'reportes/reporte_ogestion/objetivos_gestion_institucional_detalles'; ///// a nivel de intitucion detalle

$route['admin/rep/rep_og_programa/(:any)'] = 'reportes/reporte_ogestion/objetivos_gestion_programa/$1'; ///// a nivel por programa
$route['admin/rep/rep_og_programa_detalles/(:any)'] = 'reportes/reporte_ogestion/objetivos_gestion_programa_detalles/$1'; ///// a nivel de programas detalles

$route['admin/rep/rep_og_objetivo'] = 'reportes/reporte_ogestion/objetivos_gestion_objetivo'; ///// a nivel por objetivo
$route['admin/rep/rep_og_objetivo/og/(:any)'] = 'reportes/reporte_ogestion/objetivos_gestion_objetivo_detalle/$1/$2'; ///// a nivel por Objetivo a detalle
/*-------------------------Reportes - Productos Terminales --------------------*/
$route['admin/rep/rep_pterminal'] = 'reportes/reporte_pterminal/productos_terminales'; //// Menu Producto Terminal
$route['admin/rep/rep_pt_institucion'] = 'reportes/reporte_pterminal/productos_terminales_institucional'; ///// a nivel de intitucion
$route['admin/rep/rep_pt_institucion_detalles'] = 'reportes/reporte_pterminal/productos_terminales_institucional_detalles'; ///// a nivel de intitucion detalle

$route['admin/rep/rep_pt_programa/(:any)'] = 'reportes/reporte_pterminal/productos_terminales_programa/$1'; ///// a nivel por programa
$route['admin/rep/rep_pt_programa_detalles/(:any)'] = 'reportes/reporte_pterminal/productos_terminales_programa_detalles/$1'; ///// a nivel de programas detalles

$route['admin/rep/rep_pt_oprog'] = 'reportes/reporte_pterminal/list_pterminales_oprog'; ///// lista por programas
$route['admin/rep/rep_pt_obj/og/(:any)'] = 'reportes/reporte_pterminal/productos_terminales_objetivo/$1/$2'; ///// a nivel por objetivo
$route['admin/rep/rep_pt_obj/detalles/(:any)'] = 'reportes/reporte_pterminal/productos_terminales_objetivo_detalle/$1/$2'; ///// a nivel por Objetivo a detalle

$route['admin/rep/rep_pt_pprog'] = 'reportes/reporte_pterminal/list_pterminales_ptprog'; ///// lista por programas
$route['admin/rep/rep_pt_pt/pt/(:any)'] = 'reportes/reporte_pterminal/productos_terminales_pt/$1/$2/$3'; ///// a nivel de producto terminal

/*----------------------- Reportes - Ejecucion del Presupuesto -----------------------------------*/
$route['admin/rep/rep_ejec_pres'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto'; //// Menu Ejecucion Presupuesto
$route['admin/rep/rep_ejec_institucion'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_institucional'; ///// a nivel de intitucion
$route['admin/rep/rep_ejec_institucion_detalles'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_institucional_detalles'; ///// a nivel de intitucion detalle

$route['admin/rep/rep_ejec_programa/(:any)'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_programa/$1'; ///// a nivel por programa
$route['admin/rep/rep_ejec_programa_detalles/(:any)'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_programa_detalles/$1'; ///// a nivel de programas detalles

$route['admin/rep/rep_ejec_operacion'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_operacion'; ///// a nivel de Operacion
$route['admin/rep/rep_ejec_operacion/op/(:any)'] = 'reportes/reporte_ejec_presupuesto/ejecucion_presupuesto_operacion_detalle/$1/$2'; ///// a nivel de Operacion a detalle



/*--------------------------- REPORTES FORMULARIOS POA ----------------------------------*/
/*--------------------------- reportes Objetivos Estrategicos ---------------------------*/
$route['admin/reporte/obje'] = 'reportes/reporte_fpoa/iframe_objetivo_estrategico'; //// Iframe Objetivo Estrategico
$route['admin/reporte/rep_obj'] = 'reportes/reporte_fpoa/pdf_objetivo_estrategico'; //// Reporte Objetivo Estrategico
/*--------------------------- reportes Analisis de Situacion ---------------------------*/
$route['admin/reporte/anal_sit'] = 'reportes/reporte_fpoa/analisis_programas';
$route['admin/reporte_analisis_situacion/(:any)'] = 'reportes/analisis_situacion/pdf_analisis_situacion/$1';
/*--------------------------- reportes Objetivos Gestion-Pterminal ---------------------------*/
$route['admin/reporte/objges'] = 'reportes/reporte_fpoa/og_pterminal_programas'; ///// Lista Aperturas Programaticas
$route['admin/reporte/rep_og_pterminal/(:any)'] = 'reportes/reporte_fpoa/reporte_og_pterminal/$1'; //// Reporte Objetivo Gestion-Producto Terminal

//REPORTE - PRESUPUESTO
$route['rep/pres_prog'] = 'reportes/crep_pres_prog';
$route['rep/prog_lproy/(:any)'] = 'reportes/crep_pres_prog/lista_proyectos/$1';
$route['rep/pres_prog_proy/(:any)'] = 'reportes/crep_pres_prog/presupuesto_programado/$1/$2/$3';
$route['rep/pres_ejec'] = 'reportes/crep_pres_ejec';
$route['rep/pres_ejec_lproy/(:any)'] = 'reportes/crep_pres_ejec/lista_proyectos/$1';
$route['rep/pres_ejec_proy/(:any)'] = 'reportes/crep_pres_ejec/presupuesto_ejecutado/$1/$2/$3';
//REPORTE- EVALUACION
$route['rep/eva_institucional'] = 'reportes/crep_eva_institucional';
$route['rep/eva_programacion'] = 'reportes/crep_eva_programacion';
$route['rep/ev_prog/(:any)'] = 'reportes/crep_eva_programacion/evaluacion_programa/$1';

$route['rep/eva_institucional'] = 'reportes/crep_eva_institucional';
$route['rep/eva_programacion'] = 'reportes/crep_eva_programacion';


//ejecucion presupuestaria
$route['rep/ejec_pres'] = 'reportes/cseg_alerta_ejec_pres';//alerta temprana en la ejecucion del presupuesto
$route['rep/ejec_pres/prog'] = 'reportes/cseg_alerta_ejec_pres/nivel_programa';//alerta a nivel programa
$route['rep/ejec_pres/proy/(:any)'] = 'reportes/cseg_alerta_ejec_pres/ejec_pres_proy/$1';//alerta a nivel programa lista de proyectos
$route['rep/ejec_pres/unidad'] = 'reportes/cseg_alerta_ejec_pres/nivel_unidad_ejecutora';//alerta a nivel  UNIDAD EJECUTORA
$route['rep/ejec_pres/uni_proy/(:any)'] = 'reportes/cseg_alerta_ejec_pres/ejec_pres_uni_proy/$1';//alerta a nivel UNIDAD EJECUTORA lista de proyectos
$route['rep/ejec_pres/prov'] = 'reportes/cseg_alerta_ejec_pres/nivel_provincia';//alerta a nivel provincia
$route['rep/ejec_pres/prov/(:any)'] = 'reportes/cseg_alerta_ejec_pres/ejec_pres_nivel_prov_proy/$1';//alerta a nivel provincia lista de proyectos

/*======================== VALIDADOR POA - FINANCIERO ============================*/
$route['admin/combo_unidad'] = 'proyecto/combo_unidad';
$route['admin/combo_fun_uni'] = 'programacion/proyecto/combo_funcionario_unidad';
$route['admin/proy/list_proy_poa'] = 'programacion/proyecto/list_proyectos_poa';  //// lista de proyectos validador POA
$route['admin/proy/list_proy_fin'] = 'programacion/proyecto/list_proyectos_financiero';  //// lista de proyectos validador POA
$route['admin/proy/mis_acciones'] = 'programacion/proyecto/mis_acciones';  //// lista de mis Acciones, donde se encuentran mis acciones
$route['admin/proy/list_proy_ok'] = 'programacion/proyecto/list_proyectos_aprobados';  //// lista de proyectos aprobados

$route['admin/proy/add_obs'] = 'programacion/proyecto/add_obs';  //// recupera datos del proyecto
$route['admin/proy/dev_poa/(:any)'] = 'proyecto/dev_validador_top/$1';  //// asignar proyectos al validador Financiero
$route['admin/proy/asig_proy'] = 'programacion/proyecto/asignar_proyecto';  //// asignar proyectos al validador POA y FINANCIERO


/*================================ MI PROGRAMACION ============================*/
$route['admin/proy/get_proy'] = 'programacion/proyecto/get_proyecto';  //// recupera datos del proyecto
$route['admin/proy/get_resp'] = 'programacion/proyecto/get_responsables';  //// recupera datos de los responsables
$route['admin/proy/get_meta'] = 'programacion/proyecto/get_meta';  //// recupera datos de la meta x
$route['admin/proy/prioridad'] = 'programacion/proyecto/prioridad_proyecto';  //// prioridad del proyecto

$route['admin/proy/rep_list_proy/(:any)'] = 'programacion/proyecto/reporte_proyectos/$1';  //// Reporte lista de proyectos 
/*--------------------------------- TECNICO DE PLANIFICACION -------------------------------*/
$route['admin/proy/operacion/(:any)'] = 'programacion/proyecto/get_operacion/$1/$2';  //// lista de la operacion especifica
$route['proy/orequerimiento/(:any)'] = 'programacion/cprog_insumos/reporte_proyecto_insumo/$1';  //// Operacion requerimiento
$route['proy/orequerimiento_proceso/(:any)'] = 'programacion/cprog_insumos/reporte_proyecto_insumo_proceso/$1/$2';  //// Reporte Operacion requerimiento por procesos
$route['proy/orequerimiento_total/(:any)'] = 'programacion/cprog_insumos/reporte_proyecto_total/$1/$2';  //// Reporte Operacion requerimiento total
$route['proy/proceso_productos/(:any)'] = 'programacion/componente/reporte_proceso_producto_actividad/$1/$2';  //// Reporte Procesos-Productos
$route['proy/proceso_productos_consolidado/(:any)'] = 'programacion/componente/reporte_proceso_producto_consolidado/$1';  //// Reporte Precesos-Productos Consolidado TOTAL


$route['admin/proy/combo_distrital'] = 'programacion/proyecto/combo_distrital'; ////// Combo distrital
$route['admin/proy/list_proy'] = 'programacion/proyecto/list_proyectos';  //// lista de proyectos 
$route['admin/proy/list_proy_dep/(:any)'] = 'programacion/proyecto/list_proyectos_departamentos/$1';  //// lista de proyectos TOP POR DEPARTAMENTOS
$route['admin/proy/proyecto'] = 'programacion/proyecto/tecnico_operativo'; //// formularios de registro
$route['admin/proy/proyecto/(:any)'] = 'programacion/proyecto/tecnico_operativo_n/$1/$2'; //// formularios de registro
$route['admin/proy/add'] = 'programacion/proyecto/valida'; ////// validar datos del proyecto
$route['admin/proy/verif'] = 'programacion/proyecto/verif'; ////// verificando datos para la apertura programatica
$route['admin/proy/add_meta'] = 'programacion/metas/add_metas'; ////// validar metas 
$route['admin/proy/update_meta'] = 'programacion/metas/update_metas'; ////// validar metas 
$route['admin/proy/del_meta'] = 'programacion/metas/delete_meta'; ////// eliminar metas 

$route['admin/proy/verif_archivo'] = 'programacion/proyecto/verif_archivo'; ////// verificando extension del archivo
$route['admin/proy/add_arch'] = 'programacion/proyecto/subir_archivos'; //// subir archivos
$route['admin/proy/archivos/(:any)'] = 'programacion/proyecto/list_archivos/$1'; //// subir archivos
$route['admin/proy/get_arch/(:any)'] = 'programacion/proyecto/list_arch/$1/$2'; //// datos del archivo seleccionado
$route['admin/proy/del_arch/(:any)'] = 'programacion/proyecto/eliminar_archivo/$1/$2'; //// eliminar archivo pdf

$route['admin/proy/delete/(:any)'] = 'programacion/proyecto/delete_proyecto/$1/$2'; ////// eliminar proyectos 

//$route['admin/proy/rcomponentes/(:any)'] = 'programacion/componente/rep_componente/$1/$2';  //// vista reporte por componentes
//$route['admin/proy/vrepcomp'] = 'programacion/componente/componente_reporte';  //// valida reporte
//$route['admin/proy/reporte_componente'] = 'programacion/componente/componente_reporte';  //// Reporte Componente

/*-----------------------------  SGP - GERENCIA DE PROYECTOS -------------------------------*/
$route['admin/sgp/list_proy'] = 'programacion/gerencia/list_proyectos';  //// lista de proyectos en ejecucion SGP
$route['admin/sgp/proyecto'] = 'programacion/gerencia/tecnico_operativo'; //// formularios de registro SGP
$route['admin/sgp/proyecto/(:any)'] = 'programacion/gerencia/tecnico_operativo_n/$1/$2'; //// formularios de registro
$route['admin/sgp/add'] = 'programacion/gerencia/valida'; ////// validar datos del proyecto
$route['admin/sgp/add_arch'] = 'programacion/gerencia/subir_archivos'; //// subir archivos
$route['admin/sgp/archivos/(:any)'] = 'programacion/gerencia/list_archivos/$1'; //// subir archivos
$route['admin/sgp/get_arch/(:any)'] = 'programacion/gerencia/list_arch/$1/$2'; //// datos del archivo seleccionado SGP
$route['admin/sgp/get_arch/(:any)'] = 'programacion/gerencia/list_arch/$1/$2'; //// datos del archivo seleccionado SGP
$route['admin/sgp/del_arch/(:any)'] = 'programacion/gerencia/eliminar_archivo/$1/$2'; //// eliminar archivo pdf SGP
/*-----------------------------  FASES - GERENCIA DE PROYECTOS -------------------------------*/
$route['admin/sgp/fase_etapa/(:any)'] = 'programacion/faseetapa/list_fase_etapa_sgp/$1';  //// lista fase etapas  - id_proy
$route['admin/sgp/newfase/(:any)'] = 'programacion/faseetapa/nueva_fase_sgp/$1/$2/$3';  //// nueva fase SGP
$route['admin/sgp/add_fe'] = 'programacion/faseetapa/add_fase_sgp';  //// valida1 fase/etapa SGP
$route['admin/sgp/add_fe2'] = 'programacion/faseetapa/add_fase2_sgp';  //// valida2 fase/etapa SGP
$route['admin/sgp/update_f/(:any)'] = 'programacion/faseetapa/modificar_fase_sgp/$1/$2/$3';  //// opcion Modificar Fase SGP
$route['admin/sgp/fase_update'] = 'programacion/faseetapa/update_fase_etapa_sgp';  //// Valida  Modificar Fase SGP

/*-----------------------------  SGP - GERENCIA DE PROYECTOS -------------------------------*/
$route['admin/sgp/proy_cerrados'] = 'programacion/gerencia/list_proyectos_cerrados';  //// lista de proyectos cerrados SGP


/*-----------------------------  FASES  DEL PROYECTO -------------------------------*/
$route['admin/proy/fase_etapa/(:any)'] = 'programacion/faseetapa/list_fase_etapa/$1';  //// lista fase etapas  - id_proy
$route['admin/proy/newfase/(:any)'] = 'programacion/faseetapa/nueva_fase/$1/$2/$3';  //// nueva fase
$route['admin/proy/add_fe'] = 'programacion/faseetapa/add_fase';  //// valida1 fase/etapa
$route['admin/proy/add_fe2'] = 'programacion/faseetapa/add_fase2';  //// valida2 fase/etapa
$route['admin/proy/update_f/(:any)'] = 'programacion/faseetapa/modificar_fase/$1/$2/$3';  //// opcion Modificar Fase
$route['admin/proy/fase_update'] = 'programacion/faseetapa/update_fase_etapa';  //// Modificar Fase (controlador)
$route['admin/proy/off'] = 'programacion/faseetapa/encender_fase';  //// Encender Fase
$route['admin/proy/verif_fase'] = 'programacion/faseetapa/verif_fase'; //// Verificando las dependencia de la fase
$route['admin/proy/delete_fase'] = 'programacion/faseetapa/delete_fase'; //// Eliminando Fase Etapa
$route['admin/proy/get_fase'] = 'programacion/faseetapa/get_fase_activa';  //// Obitne datos de la fase para los indicadores de desemepenio
$route['admin/proy/add_indi'] = 'programacion/faseetapa/add_indicador';  //// Obitne datos de la fase para los indicadores de desemepenio
$route['admin/proy/asig_ptto/(:any)'] = 'programacion/faseetapa/asignar_presupuesto/$1'; ////// Asigan Presupuesto de la gestion vigente a la fase activa
$route['admin/proy/add_ptto'] = 'programacion/faseetapa/add_techo_presupuesto';  //// valida techo presupuestario
$route['admin/proy/ver_techo_ptto/(:any)'] = 'programacion/faseetapa/ver_techo_ptto/$1/$2';  //// ver techo presupuestario de la fase
$route['admin/proy/get_techo'] = 'programacion/faseetapa/get_techo_ptto';  //// recupera datos del techo presupuesto x
$route['admin/proy/add_ptto_techo'] = 'programacion/faseetapa/validar_techo_ptto';  //// Validar datos del techo presupuesto x
$route['admin/proy/update_techo'] = 'programacion/faseetapa/update_techo_ptto';  //// Update datos del techo presupuesto x
$route['admin/proy/delete_techo'] = 'programacion/faseetapa/delete_techo_ptto';  //// Delete datos del techo presupuesto x
$route['admin/proy/actualiza_techo_ptto'] = 'programacion/faseetapa/valida_techo_ptto';  //// ver techo presupuestario de la fase (Actualizando lo ultimo)
$route['admin/proy/delete_recurso/(:any)'] = 'programacion/faseetapa/delete_recurso/$1/$2/$3/$4';  //// Delete datos del techo presupuesto recurso x (Borrar)
/*-----------------------------  MODIFICADO DEL PROYECTO -------------------------------*/
$route['admin/proy/edit/(:any)'] = 'programacion/proyecto/ruta_edit_proy/$1/$2'; //// ruta de formularios para el editado  id, form
$route['admin/proy/update_apertura'] = 'programacion/proyecto/actualizar_apertura'; ////// Actualizar apertura programatica
$route['admin/proy/update'] = 'programacion/proyecto/actualizar_datos'; ////// Actualizar datos proyecto

$route['admin/proyn/edit/(:any)'] = 'programacion/proyecto_nuevo/ruta_edit_proy/$1/$2'; //// ruta de formularios para el editado  id, form
$route['admin/proyn/update_apertura'] = 'programacion/proyecto_nuevo/actualizar_apertura'; ////// Actualizar apertura programatica
$route['admin/proyn/update'] = 'programacion/proyecto_nuevo/actualizar_datos'; ////// Actualizar datos proyecto



/*-----------------------------  MODIFICADO DEL PROYECTO SGP -------------------------------*/
$route['admin/sgp/edit/(:any)'] = 'programacion/gerencia/ruta_edit_proy/$1/$2'; //// ruta de formularios para el editado  id, form SGP
$route['admin/sgp/update'] = 'programacion/gerencia/actualizar_datos'; ////// Actualizar datos proyecto SGP
$route['admin/proy/history/(:any)'] = 'programacion/proyecto/historial_usuarios/$1'; /////// Historial de usuarios responsables

/*-----------------------------  PROGRAMACION DEL PROYECTO - DATOS GENERALES -------------------------------*/
$route['admin/proy/prog/(:any)'] = 'programacion/datosgenerales/dashboard_programacion/$1/$2';  //// Dashboard del componente - id_proy 
$route['admin/proy/mis_proyectos/(:any)'] = 'programacion/datosgenerales/mis_proyectos/$1';  //// lista de proyectos anual/multianual
$route['admin/proy/datos_proy/(:any)'] = 'programacion/datosgenerales/datos_generales/$1/$2/$3';  //// Datos generales
$route['admin/proy/edit_prog/(:any)'] = 'programacion/datosgenerales/ruta_edit_proy/$1/$2/$3'; //// ruta de formularios para el editado mod id, form
$route['admin/proy/update_prog'] = 'programacion/datosgenerales/actualizar_datos'; ////// Actualizar datos del proyecto 
$route['admin/proy/add_archivo'] = 'programacion/datosgenerales/subir_archivos'; //// subir archivos
$route['admin/prog/update_fase/(:any)'] = 'programacion/datosgenerales/modificar_fase/$1/$2/$3/$4';  //// opcion Modificar Fase
$route['admin/prog/fase_update'] = 'programacion/datosgenerales/update_fase_etapa';  //// Modificar Fase (controlador)

/*-----------------------------  PROGRAMACION DEL PROYECTO - PROGRAMACION FISICA  -------------------------------*/
$route['admin/prog/prog_fisica/(:any)'] = 'programacion/datosgenerales/programacion_fisica/$1/$2/$3';  //// programacion fisica del proyecto


/*-----------------------------  PROGRAMACION DEL PROYECTO - COMPONENTES  -------------------------------*/
$route['admin/prog/list_comp/(:any)'] = 'programacion/componente/lista_componentes/$1/$2/$3';  //// listado componente de un proyecto normal
$route['admin/prog/add_comp'] = 'programacion/componente/valida_componente';  //// listado componente de un proyecto
$route['admin/prog/get_comp'] = 'programacion/componente/get_componente';  //// recupera datos del componente x
$route['admin/prog/update_comp'] = 'programacion/componente/update_componente'; ////// validar update componente 
$route['admin/prog/delete_comp/(:any)'] = 'programacion/componente/delete_componente/$1/$2/$3/$4'; //// Eliminando el componente

$route['admin/prog/verif_act'] = 'programacion/componente/anula_actividades'; //// Anula Actividades
$route['admin/prog/verif_comp'] = 'programacion/componente/verif_nro'; //// verifica nro de componente

$route['admin/prog/subir_archivo_producto'] = 'programacion/componente/archivo_productos'; //// verifica nro de componente

/*-----------------------------  PROGRAMACION DEL PROYECTO - PRODUCTOS  -------------------------------*/
$route['admin/prog/list_prod/(:any)'] = 'programacion/producto/lista_productos/$1/$2/$3/$4';  //// lista de productos

$route['admin/prog/valida_upload_prod'] = 'programacion/producto/subir_producto';  //// Subir Archivo Productos txt
$route['admin/prog/plist_prod/(:any)'] = 'programacion/producto/pre_lista_productos/$1/$2/$3/$4/$5';  //// pre lista de Productos
$route['admin/prog/subir_prod/(:any)'] = 'programacion/producto/validar_pre_lista_productos/$1/$2/$3/$4/$5';  //// Validar pre lista de Productos
$route['admin/prog/delete_prod_temp/(:any)'] = 'programacion/producto/borrar_productos_temporal/$1/$2/$3/$4/$5';  //// Eliminar pre lista de Productos
$route['admin/prog/exportar_productos/(:any)'] = 'programacion/producto/exportar_productos/$1';  //// Exportar lista de Productos


$route['admin/prog/new_prod/(:any)'] = 'programacion/producto/new_productos/$1/$2/$3/$4';  //// formulario agregar productos
$route['admin/prog/add_prod'] = 'programacion/producto/valida_producto';  //// valida productos
$route['admin/prog/mod_prod/(:any)'] = 'programacion/producto/update/$1/$2/$3/$4/$5';  ////  formulario editado productos 
$route['admin/prog/update_prod'] = 'programacion/producto/modificar_producto';  //// modificar componente
$route['admin/prog/delete_prod/(:any)'] = 'programacion/producto/delete_producto/$1/$2/$3/$4/$5'; //// Eliminando el producto

/*-----------------------------  PROGRAMACION DEL PROYECTO - ACTIVIDADES  -------------------------------*/
$route['admin/prog/list_act/(:any)'] = 'programacion/actividades/lista_actividades/$1/$2/$3/$4/$5';  //// lista de actividades
//$route['admin/prog/upload_act/(:any)'] = 'programacion/actividades/archivo_actividad/$1/$2/$3/$4/$5';  //// lista de actividades con subida
$route['admin/prog/valida_upload_act'] = 'programacion/actividades/subir_actividad';  //// Subir Archivo Actividades
$route['admin/prog/plist_act/(:any)'] = 'programacion/actividades/pre_lista_actividades/$1/$2/$3/$4/$5/$6';  //// pre lista de actividades
$route['admin/prog/subir_act/(:any)'] = 'programacion/actividades/validar_pre_lista_actividades/$1/$2/$3/$4/$5/$6';  //// Validar pre lista de actividades
$route['admin/prog/delete_act_temp/(:any)'] = 'programacion/actividades/borrar_actividad_temporal/$1/$2/$3/$4/$5/$6';  //// Eliminar pre lista de actividades

$route['admin/prog/new_act/(:any)'] = 'programacion/actividades/new_actividad/$1/$2/$3/$4/$5';  ////  formulario de actividades
$route['admin/prog/add_act'] = 'programacion/actividades/valida_actividad';  //// valida actividades
$route['admin/prog/mod_act/(:any)'] = 'programacion/actividades/update/$1/$2/$3/$4/$5';  ////  formulario editado actividades 
$route['admin/prog/update_act'] = 'programacion/actividades/modificar_actividad';  //// modificar actividad
$route['admin/prog/delete_act/(:any)'] = 'programacion/actividades/delete_actividades/$1/$2/$3/$4/$5/$6'; //// Eliminando la actividad
/*-----------------------------  PROGRAMACION DEL PROYECTO - EJECUCION  FISICA-------------------------------*/
$route['admin/prog/efisica/(:any)'] = 'programacion/producto/lista_prod_efisica/$1/$2/$3';  //// lista de Productos ejecucion fisica
$route['admin/prog/ejec_prod/(:any)'] = 'programacion/producto/ejecucion_producto/$1/$2/$3';  //// ejecucion producto
$route['admin/prog/valida_ejec_prod'] = 'programacion/producto/valida_ejecucion_producto';  //// ejecucion producto
$route['admin/prog/efisica_a/(:any)'] = 'programacion/actividades/lista_act_efisica/$1/$2/$3/$4/$5';  //// lista de actividades ejecusion fisica
$route['admin/prog/ejec_act/(:any)'] = 'programacion/actividades/ejecucion_actividad/$1/$2/$3/$4/$5/$6';  //// ejecucion Actividad
$route['admin/prog/valida_ejec_act'] = 'programacion/actividades/valida_ejecucion_actividad';  //// ejecucion actividad

/*-----------------------------  PROGRAMACION DEL PROYECTO - EJECUCION FINANCIERA -------------------------------*/
$route['admin/prog/efinanciero/(:any)'] = 'programacion/datosgenerales/lista_partidas/$1/$2/$3';  //// Ejecucion Financiera segun tipo de ejecucion
$route['admin/prog/ejec_partida/(:any)'] = 'programacion/datosgenerales/ejecutar_partida/$1/$2/$3/$4';  //// Ejecutar Partida
$route['admin/prog/valida_ejecucion'] = 'programacion/datosgenerales/add_ejecucion';  //// Valida Ejecucion


/*-----------------------------  PROGRAMACION DEL PROYECTO - REPORTES  -------------------------------*/
$route['admin/prog/reporte_proy/(:any)'] = 'programacion/reportes/reporte_proyecto/$1/$2/$3';  //// Identificacion del proyecto
$route['admin/prog/identificacion_proy/(:any)'] = 'programacion/reportes/identificacion_proyecto/$1/$2/$3';  //// ejecucion programacion fisica

$route['admin/prog/reporte_fis/(:any)'] = 'programacion/reportes/reporte_fisico/$1/$2/$3';  //// lista de productos
$route['admin/prog/ejecucion_pfisico/(:any)'] = 'programacion/reportes/reporte_programacion_fisica/$1/$2/$3';  //// ejecucion programacion fisica anual
$route['admin/prog/ejecucion_pfisico_m/(:any)'] = 'programacion/reportes/reporte_programacion_fisica_m/$1/$2/$3';  //// ejecucion programacion fisica multi anual

$route['admin/prog/reporte_fin/(:any)'] = 'programacion/reportes/reporte_financiero/$1/$2/$3';  //// iframe reporte Presupuestario
$route['admin/prog/rep_financiero/(:any)'] = 'programacion/reportes/reporte_programacion_financiera/$1/$2/$3';  //// reporte Presupuestario

$route['admin/prog/contrato/(:any)'] = 'programacion/reportes/reporte_contrato/$1/$2/$3';  //// reporte de contrato
$route['admin/prog/reporte_contrato/(:any)'] = 'programacion/reportes/contrato_proyecto/$1/$2';  ////pdf contrato

$route['admin/prog/supervision/(:any)'] = 'programacion/reportes/reporte_supervision/$1/$2/$3';  //// reporte de contrato
$route['admin/prog/reporte_supervision/(:any)'] = 'programacion/reportes/supervision_evaluacion/$1/$2';  ////pdf contrato

$route['admin/prog/curva_s/(:any)'] = 'programacion/reportes/grafico_fisico/$1/$2/$3';  ////Grafico Curva S

/*--------------------------======  PROGRAMACION DEL PROYECTO - REPORTES  -------------------------------*/
$route['admin/identificacion/(:any)']='programacion/reportes/identificacion_proyecto/$1';// Identificacion de la Etapa ACtiva
$route['admin/rep_programacion/(:any)']='programacion/reportes/programacion_fisica/$1/$2/$3';// Programacion y ejecucion Fisica de la Etapa Anual-PluriAnual

$route['admin/rep_financiero/(:any)']='programacion/reportes/programacion_financiera/$1/$2/$3';// Programacion y ejecucion Financiero de la Etapa Anual-PluriAnual
$route['admin/financiero_directo/(:any)'] = 'programacion/creporte_proy/reporte_proy_ejecfin_directo/$1'; //// financiero DIRECTO
$route['admin/financiero_delegado/(:any)'] = 'programacion/creporte_proy/reporte_proy_ejecfin_delegado/$1'; //// financiero DELEGADO
$route['admin/reporte_proy_ejecfin_m/(:any)'] = 'programacion/reportes/reporte_proy_ejecfin_m/$1'; //// financiero DELEGADO PLURI ANUAL

$route['admin/contratos/(:any)'] = 'programacion/reportes/contrato_proyecto/$1/$2'; // Seguimiento de Contratos
$route['admin/supervision_evaluacion/(:any)'] = 'programacion/reportes/supervision_evaluacion/$1/$2'; // Supervision y evaluacion

$route['admin/rep_curva_fis/(:any)']='programacion/reportes/grafico_programacion_fisica/$1/$2/$3';// Curva S Programado Fisico
$route['admin/imprimir_curva_fis/(:any)']='programacion/reportes/imprimir_programacion_fisica/$1/$2/$3';// IMPRIMIR Curva S Programado Fisico
$route['admin/rep_curva_fin/(:any)'] = 'programacion/creporte_avance_fin/grafico_programacion_financiero/$1/$2/$3'; /// Curva S Programado Financiero
$route['admin/imprimir_curva_fin/(:any)'] = 'programacion/creporte_avance_fin/imprimir_programacion_financiero/$1/$2/$3'; /// Curva S Programado Financiero

//----------- PROGRAMACION REPORTE FINANCIERO
$route['proy/reporte/ejec_fin/(:any)'] = 'programacion/creporte_proy/reporte_ejec_fin/$1/$2/$3';
$route['proy/reporte_ejec_fin/(:any)'] = 'programacion/creporte_proy/reporte_proy_ejecfin_directo/$1';
$route['proy/avance_financiero/(:any)'] = 'programacion/creporte_avance_fin/main/$1/$2/$3'; //// Iframe

/*================================ REGISTRO DE EJECUCION ============================*/
$route['admin/combo_estado'] = 'ejecucion/ejecucion/combo_estados_proy';

$route['admin/ejec/mis_operaciones'] = 'ejecucion/ejecucion/mis_operaciones';  //// Mis Operaciones
$route['admin/ejec/meses_operacion/(:any)'] = 'ejecucion/ejecucion/list_meses/$1/$2/$3/$4';  //// lista de meses por componentes para la ejecucion
$route['admin/ejec/proy/(:any)'] = 'ejecucion/ejecucion/proyecto_n/$1/$2/$3'; //// Formulario de Registro - Ejecucion
$route['admin/ejec/valida'] = 'ejecucion/ejecucion/valida'; //// valida datos del proyecto en su ejecucion
$route['admin/ejec/archivos/(:any)'] = 'ejecucion/ejecucion/list_archivos/$1/$2/$3';  //// Anexos de la Ejecucion
$route['admin/ejec/valida_arch'] = 'ejecucion/ejecucion/valida_archivo'; //// valida archivo
$route['admin/ejec/delete_anexo_ejec/(:any)'] = 'ejecucion/ejecucion/borrar_archivo_anexo_ejecucion/$1/$2/$3/$4/$5/$6'; //// Eliminar archivo
$route['admin/ejec/reporte_ejecucion/(:any)'] = 'ejecucion/ejecucion/reporte_ejecucion_mensual/$1/$2/$3/$4/$5/$6'; //// Reporte Mensual


$route['admin/ejec/list_proy/(:any)'] = 'ejecucion/ejecucion/list_proyectos/$1';  //// lista de proyectos para el registro de la ejecucion
$route['admin/ejec/proy_comp/(:any)'] = 'ejecucion/ejecucion/proyecto_componente/$1/$2'; //// redireccionando a sus componentes
$route['admin/ejec/update_proy'] = 'ejecucion/ejecucion/update_proyecto'; //// update cerrar proyecto


$route['admin/ejec/ver/(:any)'] = 'ejecucion/ejecucion/verificando_proy/$1/$2'; //// verificando los meses del proyecto
$route['admin/ejec/ejecucion_mes/(:any)'] = 'ejecucion/ejecucion/ejecucion_mes/$1/$2/$3/$4'; //// mostrar mes de ejecucion
$route['admin/ejec/valida_obs'] = 'ejecucion/ejecucion/valida_observacion'; //// valida obervacion
$route['admin/ejec/get_obs'] = 'ejecucion/ejecucion/get_observacion'; //// valida obervacion
$route['admin/ejec/update_obs'] = 'ejecucion/ejecucion/update_observacion'; //// valida obervacion
$route['admin/ejec/delete_obs'] = 'ejecucion/ejecucion/delete_observacion'; //// delete contrato
$route['admin/ejec/proy_comp_meses/(:any)'] = 'ejecucion/ejecucion/proyecto_componente_meses/$1/$2';  //// componentes por meses
//$route['admin/ejec/meses/(:any)'] = 'ejecucion/ejecucion/list_meses/$1/$2/$3/$4';  //// lista de meses por meses
$route['admin/ejec/revertir'] = 'ejecucion/ejecucion/revertir_mes';  //// revertir ejecucion
$route['admin/ejec/verm/(:any)'] = 'ejecucion/ejecucion/verificando_proy_mes/$1/$2/$3'; //// verificando el mes x del proyecto
$route['admin/ejec/documentos/(:any)'] = 'ejecucion/ejecucion/archivos_mes/$1/$2/$3'; //// lista de archivos del mes x
$route['admin/ejec/get_archivo/(:any)'] = 'ejecucion/ejecucion/get_archivo/$1/$2/$3/$4'; //// datos del archivo seleccionado

/*----------------------------- EJECUCION - CERTIFICACION POA (TUE) -------------------------------*/
$route['ejec/menu_cpoa'] = 'ejecucion/cert_poa/menu_certificacion_poa'; //// Menu Tecnico de Unidad Ejecutora
$route['ejec/cpoa/(:any)'] = 'ejecucion/cert_poa/list_certificados_poa/$1'; //// Lista de Certificados POA solicitados, aprobados
$route['ejec/generar_cpoa'] = 'ejecucion/cert_poa/cpoa_lista_programas'; //// Generar Certificados POA 
$route['ejec/cpoa_requerimiento/(:any)'] = 'ejecucion/cert_poa/cpoa_operacion_requerimiento_prod/$1/$2/$3'; //// Certificados POA Requerimientos a nivel de productos
$route['ejec/valida_cpoa_requerimiento'] = 'ejecucion/cert_poa/valida_cpoa_operacion_requerimiento'; //// Valida Certificacion POA
$route['ejec/mod_certificacion/(:any)'] = 'ejecucion/cert_poa/modificar_certificacion/$1/$2'; //// Modificar Certificacion POA
$route['ejec/valida_update_cpoa_requerimiento'] = 'ejecucion/cert_poa/valida_update_cpoa_operacion_requerimiento'; //// Valida Update Certificacion POA
$route['ejec/ver_certificado_poa/(:any)'] = 'ejecucion/cert_poa/ver_reporte_certificado_poa/$1'; //// Iframe reporte Certificado POA
$route['ejec/certificado_poa/(:any)'] = 'ejecucion/cert_poa/reporte_certificado_poa/$1'; //// reporte Certificado POA
$route['ejec/validar_cert'] = 'ejecucion/cert_poa/validar_certificacion';  //// Valida para la certificacion POA
$route['ejec/get_requerimiento'] = 'ejecucion/cert_poa/get_requerimiento';  //// recupera datos del requerimiento
/*----------------------------- EJECUCION - CERTIFICACION POA (POA) -------------------------------*/
$route['ejec/menu_vpoa'] = 'ejecucion/cert_poa/menu_certificaciones'; //// Certificaciones poas VPOA
$route['ejec/rechazar_cert'] = 'ejecucion/cert_poa/rechazar_certificacion';  //// Valida para recchazar la certificacion POA


/*----------------------------- EJECUCION - CONTRATOS -------------------------------*/
$route['admin/ejec/list_contratos/(:any)'] = 'ejecucion/ejecucion/lista_contratos/$1/$2/$3/$4'; //// lista de contrato
$route['admin/ejec/new_contrato/(:any)'] = 'ejecucion/ejecucion/contrato/$1/$2/$3/$4'; //// nuevocontrato
$route['admin/ejec/add_contrato'] = 'ejecucion/ejecucion/valida_contrato'; //// valida contrato
$route['admin/ejec/add_ctta'] = 'ejecucion/ejecucion/valida_contratista'; //// valida contrato
$route['admin/ejec/edit_contrato/(:any)'] = 'ejecucion/ejecucion/update_contrato/$1/$2/$3/$4'; //// update contrato
$route['admin/ejec/update_contrato'] = 'ejecucion/ejecucion/valida_update_contrato'; //// valida update contrato
$route['admin/ejec/delete_contrato'] = 'ejecucion/ejecucion/delete_contrato'; //// delete contrato

/*----------------------------- EJECUCION - GARANTIAS -------------------------------*/
$route['admin/ejec/garantias/(:any)'] = 'ejecucion/ejecucion/lista_garantias/$1/$2/$3/$4/$5'; //// lista de contrato
$route['admin/ejec/new_garantia/(:any)'] = 'ejecucion/ejecucion/garantia/$1/$2/$3/$4/$5'; //// nueva garantia
$route['admin/ejec/add_garantia'] = 'ejecucion/ejecucion/valida_garantia/'; //// valida garantia
$route['admin/ejec/edit_garantia/(:any)'] = 'ejecucion/ejecucion/update_garantia/$1/$2/$3/$4/$5/$6'; //// update garantia
$route['admin/ejec/update_garantia'] = 'ejecucion/ejecucion/valida_update_garantia'; //// valida update garantia
$route['admin/ejec/delete_garantia'] = 'ejecucion/ejecucion/delete_garantia'; //// delete garantia
/*----------------------------- EJECUCION - PEI -------------------------------*/
$route['admin/ejec/redobj'] = 'ejecucion/ejecucion/cprog_red_objetivos'; //// lista de red de objetivos
$route['admin/ejec/obj_est/(:any)'] = 'ejecucion/ejecucion/objetivo_estrategico/$1'; //// Objetivo a Ejecutar
$route['admin/ejec/valida_oe'] = 'ejecucion/ejecucion/valida_oe'; //// Valida Objetivo estrategico
$route['admin/ejec/obj_update/(:any)'] = 'ejecucion/ejecucion/objetivo_estrategico_update/$1'; //// Objetivo a Ejecutar
$route['admin/ejec/obj_cerrado/(:any)'] = 'ejecucion/ejecucion/objetivo_estrategico_cerrado/$1'; //// Objetivo a Ejecutar cerrado
$route['admin/ejec/valida_obs_oe'] = 'ejecucion/ejecucion/valida_observacion_oe'; //// valida obervacion objetivos estrategicos
$route['admin/ejec/get_obs_oe'] = 'ejecucion/ejecucion/get_observacioarchivos_oen_oe'; //// get observacion obj. est
$route['admin/ejec/update_obs_oe'] = 'ejecucion/ejecucion/update_observacion_oe'; //// valida obervacion oe
$route['admin/ejec/delete_obs_oe'] = 'ejecucion/ejecucion/delete_observacion_oe'; //// delete observacion oe
$route['admin/ejec/archivos_oe/(:any)'] = 'ejecucion/ejecucion/archivos_adjuntos_oe/$1'; //// archivos adjuntos
$route['admin/ejec/valida_arch_oe'] = 'ejecucion/ejecucion/valida_archivo_oe'; //// valida archivo oe
$route['admin/ejec/delete'] = 'ejecucion/ejecucion/delete_archivo'; ////// eliminar metas 


/*-------------------------------------- MODIFICACIONES DE OPERACIONES (ULTIMO)---------------------------------*/
$route['mod/ope_aprobadas'] = 'modificaciones/cmodificaciones/operaciones_aprobadas';  ///// lista de operaciones aprobadas
$route['mod/derivar_operacion'] = 'modificaciones/cmodificaciones/derivar_operacion';  ///// derivar Operacion a TOP
$route['mod/list_top'] = 'modificaciones/cmodificaciones/list_top';  ///// Lista de Operaciones TOP
$route['mod/list_vpoa'] = 'modificaciones/cmodificaciones/list_vpoa';  ///// Lista de Operaciones VPOA
$route['mod/list_vfin'] = 'modificaciones/cmodificaciones/list_vfin';  ///// Lista de Operaciones VFIN

$route['mod/cites_mod/(:any)'] = 'modificaciones/cmodificaciones/cites_modificacion/$1/$2';  ///// Lista de Cites Generados
/*------------- Modificar Reuqerimientos ------------*/
$route['mod/cite_modificacion'] = 'modificaciones/cmod_requerimientos/valida_cite_modificacion'; //// Cite Modifcacion

$route['mod/procesos/(:any)'] = 'modificaciones/cmod_requerimientos/procesos/$1'; //// Procesos de la operacion Institucional
$route['mod/mod_requerimiento/(:any)'] = 'modificaciones/cmod_requerimientos/requerimientos/$1/$2/$3'; //// Lista de Requerimientos
$route['mod/update_requerimiento/(:any)'] = 'modificaciones/cmod_requerimientos/update_requerimiento/$1/$2/$3/$4'; //// Update Requerimiento
$route['mod/valida_update_requerimiento'] = 'modificaciones/cmod_requerimientos/valida_update_requerimiento'; //// Valida Update Requerimiento
$route['mod/update_temporalizacion/(:any)'] = 'modificaciones/cmod_requerimientos/update_temporalizacion/$1/$2/$3/$4/$5'; //// Update Temporalizacion
$route['mod/valida_update_temporalizacion'] = 'modificaciones/cmod_requerimientos/valida_update_temporalizacion'; //// Valida Update Temporalizacion

$route['mod/add_requerimiento/(:any)'] = 'modificaciones/cmod_requerimientos/add_requerimiento/$1/$2/$3/$4'; //// Agrega Requerimiento
$route['mod/add_temporalidad/(:any)'] = 'modificaciones/cmod_requerimientos/add_temporalidad/$1/$2/$3/$4'; //// Agrega Temporalidad

$route['mod/delete_requerimiento'] = 'modificaciones/cmod_requerimientos/delete_requerimiento'; //// Elimina Requerimiento

/*------------- Modificar Productos -----------------*/
$route['admin/mod/modificar'] = 'modificaciones/cmodificaciones/modificar'; //// modificar Operacion 
$route['admin/mod/cite_operacion/(:any)'] = 'modificaciones/cmodificaciones/cite_operacion/$1'; //// Cite Operacion
$route['mod/cite_modificacion_ope'] = 'modificaciones/cmodificaciones/valida_cite_operacion'; //// Cite Valida Operacion
$route['admin/mod/proyecto_mod/(:any)'] = 'modificaciones/cmodificaciones/redireccionar_modicacion/$1/$2'; //// redireccionar al tipo de formulario 
$route['admin/mod/producto/(:any)'] = 'modificaciones/cmodificaciones/modificar_producto/$1/$2/$3'; //// modificar producto 
$route['admin/mod/valida_mp'] = 'modificaciones/cmodificaciones/valida_producto'; //// valida modificacion producto

$route['admin/mod/actividad/(:any)'] = 'modificaciones/cmodificaciones/modificar_actividad/$1/$2/$3'; //// modificar producto 
$route['admin/mod/valida_ma'] = 'modificaciones/cmodificaciones/valida_actividad'; //// valida modificacion actividad

$route['admin/mod/valida_plazo'] = 'modificaciones/cmodificaciones/valida_plazo'; //// valida modificacion plazo de ejecucion
$route['admin/mod/valida_pr'] = 'modificaciones/cmodificaciones/valida_presupuesto'; //// valida modificacion presupuesto

$route['admin/mod/techo_p/(:any)'] = 'modificaciones/cmodificaciones/modificar_techo_presupuesto/$1/$2/$3/$4'; //// modificar presupuesto 
$route['admin/mod/add_ptto_techo'] = 'modificaciones/cmodificaciones/validar_techo_ptto'; //// valida  presupuesto
$route['admin/mod/update_techo'] = 'modificaciones/cmodificaciones/update_techo_ptto'; //// valida modificacion presupuesto


/*======================================= MODIFICACIONES ==========================================================*/
$route['admin/mod/redobj'] = 'modificaciones/modificaciones/cprog_red_objetivos'; /// Lista de red de objetivos por gestion
$route['admin/mod/objetivo/(:any)'] = 'modificaciones/modificaciones/objetivos_estrategicos/$1'; //// objetivos estrategicos
$route['admin/mod/obj_gest/(:any)'] = 'modificaciones/modificaciones/objetivo_gestion/$1/$2'; //// objetivos de gestion y productos terminales
$route['admin/mod/mod_obj_gest/(:any)'] = 'modificaciones/modificaciones/modificar_objetivo_gestion/$1/$2/$3'; //// Modificar Objetivo de Gestion
$route['admin/mod/valida_objetivo'] = 'modificaciones/modificaciones/valida_obj_gestion'; /// valida objetivo de gestion modificado
$route['admin/mod/mod_pt/(:any)'] = 'modificaciones/modificaciones/modificar_producto_terminal/$1/$2/$3'; //// Modificar producto terminal de Gestion
$route['admin/mod/valida_pt'] = 'modificaciones/modificaciones/valida_pt'; /// valida producto terminal modificado




//========================    REPORTES    ==================================
//============================  SEGUIMIENTO OBJETIVO DE GESTION
$route['admin/seg/mo'] = 'reportes/seguimiento/seg_menu_ogestion';
$route['admin/seg/og/(:any)'] = 'reportes/seguimiento/seg_por_prog/$1';//seguimiento por programas
$route['admin/seg/obje_gestion/(:any)'] = 'reportes/seguimiento/seg_por_ogestion/$1'; //seguimiento por objetivo de gestion
$route['admin/seg/inst'] = 'reportes/seguimiento/seg_pe_institucion'; //seguimiento por institucion
//============================  SEGUIMIENTO PRODUCTO TERMINAL
$route['admin/seg/mpt'] = 'reportes/seguimiento/seg_menu_pt';
$route['admin/seg/pe_pt'] = 'reportes/seguimiento/seg_pe_pt';
$route['admin/seg/o_pt/(:any)'] = 'reportes/seguimiento/seg_ogestion_productot/$1';
$route['admin/seg/pt/(:any)'] = 'reportes/seguimiento/seg_por_pt/$1/$2'; //seguimiento por producto terminal
$route['admin/seg/n_o/(:any)'] = 'reportes/seguimiento/nivel_ogestion/$1';//seguimiento a nivel objetivo de gestion
$route['admin/seg/gopt/(:any)'] = 'reportes/seguimiento/grafico_por_gestionpt/$1/$2';//grafico nivel de objetivo de gestion producto terminal
$route['admin/seg/prog_pt/(:any)'] = 'reportes/seguimiento/nivel_programa_pt/$1';//seguimiento a nivel programas
$route['admin/seg/inst_pt'] = 'reportes/seguimiento/nivel_institucion_pt';//seguimiento a nivel institucion
//=========================== SEGUIMIENTO PRODUCTO DE LA OPERACION
$route['admin/seg/mop'] = 'reportes/seguimiento/menu_producto_operacion';//MENU de seguimiento producto de la opracion
$route['admin/seg/mop1'] = 'reportes/seguimiento/seg_menu_operacion';//seguimiento producto de la opracion por prog y ejec
$route['admin/seg/mop2'] = 'reportes/seguimiento/seg_menu_op_fisico';//seguimiento producto de la opracion
//$route['admin/seg/o_op/(:any)'] = 'reportes/seguimiento/lista_ogestion_ope/$1';//lista de objetivo de gestion para los productos de la opracion
$route['admin/seg/o_op/(:any)'] = 'reportes/seguimiento/lista_proyectos/$1';//lista de proyectos para los productos de la opracion
$route['admin/seg/pt_ope/(:any)'] = 'reportes/seguimiento/lista_pt_ope/$1';//lista de productos terminales para los productos de la opracion
$route['admin/seg/pe_po/(:any)'] = 'reportes/seguimiento/prog_ejec_ope/$1/$2/$3';//programacion y ejecucion de las operaciones
$route['admin/seg/graf_prod'] = 'reportes/seguimiento/grafico_prod_mes';//grafico de producto de la operacion por mes
$route['admin/seg/proy_po/(:any)'] = 'reportes/seguimiento/nivel_proyecto_po/$1';//seguimiento a nivel proyecto
$route['admin/seg/gproy/(:any)'] = 'reportes/seguimiento/grafico_por_proyecto_op/$1/$2/$3';//grafico nivel de proyecto de producto de la operacion
$route['admin/seg/prog_po/(:any)'] = 'reportes/seguimiento/nivel_programa_po/$1';//seguimiento a nivel programas
$route['admin/seg/inst_po'] = 'reportes/seguimiento/nivel_institucion_po';//seguimiento a nivel institucion producto de la operacion
//producto de la operacion fisico
$route['admin/seg/proy_pof/(:any)'] = 'reportes/seguimiento/nivel_proyecto_pof/$1';//seguimiento a nivel de proyecto
$route['admin/seg/graf_proyf'] = 'reportes/seguimiento/grafico_proy_fisico';//seguimiento a nivel de proyecto
$route['admin/seg/prog_pof/(:any)'] = 'reportes/seguimiento/nivel_programa_pof/$1';//seguimiento a nivel programas FISICO
$route['admin/seg/inst_pof'] = 'reportes/seguimiento/nivel_institucion_pof';//seguimiento a nivel institucion producto de la operacion FISICO
//REPORTES GERENCIALES
$route['admin/rg/seg'] = 'reportes/reportes_gerenciales/menu_reporte_gerencial';//reportes gerenciales



/*========================================= CONTROL SOCIAL ======================================*/
$route['admin/validate_invitado'] = 'user/validate_invitado';// validar al control social
$route['admin/control_social'] = 'reportes/control_social/mis_acciones';// Mis Acciones

//=========================  PRESUPUESTO ===============================
$route['admin/fp/pg'] = 'reportes/presupuesto/presupuesto_gasto';// presupuesto de gasto
$route['admin/fp/lp/(:any)'] = 'reportes/presupuesto/lista_proyectos/$1';// lista de proyectos
$route['admin/fp/lpar/(:any)'] = 'reportes/presupuesto/lista_partidas/$1/$2/$3';// lista de proyectos
$route['admin/pr/lproy/(:any)'] = 'reportes/presupuesto/lista_proy_por_mes/$1/';// lista de presupuesto de proyectos por mes
$route['admin/pr/lpar_mes/(:any)'] = 'reportes/presupuesto/lista_par_por_mes/$1/$2/$3';// lista de presupuesto de proyectos por mes
$route['admin/pr/inst'] = 'reportes/presupuesto/pres_por_institucion';//PRESUPUESTO POR INSTITUCION
$route['admin/pr/graf_proy'] = 'reportes/presupuesto/grafico_proyecto_mes';//GRAFICOS PROYECTO
$route['admin/pr/graf_prog'] = 'reportes/presupuesto/grafico_programa_mes';//GRAFICOS PROGRAMA



/////////////////////////////////////pdf//////////////////////////////////////////////
$route['admin/mantenimiento/hardy'] = 'a_pdf/b_pdf';
//////////////////////////////////////////////////////
$route['admin/pdf/obj_pdf/(:any)'] = 'a_pdf/obj_pdf/$1/$2';
$route['admin/pdf/objs_pdf/(:any)'] = 'a_pdf/obj_dompdf/$1/$2';
///////////////////////////////////////REPORTES////////////////////////////////
$route['admin/reportes/objes'] = 'reportes/reporte/ficha_tecnica';
$route['admin/reportes/objgest'] = 'reportes/reporte/ficha_obge';
$route['admin/reportes/prter'] = 'reportes/reporte/ficha_proter';
$route['admin/reportes/productot'] = 'reportes/reporte/ficha_productot';
$route['admin/reportes/productots'] = 'reportes/reporte/repor_protot';
$route['admin/reportes/report_ges/(:any)'] = 'reportes/reporte/reporte_gestion/$1/$2';
//////////////////////////////////////////////////////////////////////////////
$route['admin/reportes/objgest_f'] = 'reportes/reporte/rep_estra';
$route['admin/reportes/lista_g'] = 'reportes/reporte/rep_gest';
$route['admin/reportes/lista_t'] = 'reportes/reporte/rep_productot';
$route['admin/reportes/lista_pordtot/(:any)'] = 'reportes/reporte/ficha_pdfpt/$1/$2/$3';
////titulos///
$route['admin/reportes/objgest_titulo'] = 'reportes/reporte/titulo_estra';
$route['admin/reportes/gest_titulo'] = 'reportes/reporte/titulo_gestion';
$route['admin/reportes/term_titulo'] = 'reportes/reporte/titulo_terminal';
//////////reporte gestion
$route['admin/reportes/reptn_gestionn/(:any)'] = 'reportes/reporte/report_gestion/$1/$2';
$route['admin/reportes/reptn_terminal/(:any)'] = 'reportes/reporte/report_pord_terminal/$1';
$route['admin/reportes/rep_terminal/(:any)'] = 'reportes/reporte/ficha_productoterminal/$1';
//////////////////////////////conotrol social/////////////////////////////////
$route['admin/controls'] = 'controlsocial/vista';
////////////////////////////login session/////////////////////////////////////
$route['admin/logins'] = 'user/login_exit';
//////////////////////////elimnar insumos/////////////////
$route['admin/del_insumos'] = 'insumos/programacion_insumos/del_insumos';



/*------------------------------------ FUNCIONARIOS --------------------------------------*/
$route['admin/mnt/list_usu'] = 'mantenimiento/funcionario/list_usuarios';  //// lista de usuarios
$route['admin/funcionario/new_fun'] = 'mantenimiento/funcionario/new_funcionario'; // new funcionario
$route['funcionario/verif_usuario'] = 'mantenimiento/funcionario/verif_usuario'; // verifica usuario
$route['admin/funcionario/add_fun'] = 'mantenimiento/funcionario/add_funcionario'; // valida funcionario
$route['admin/funcionario/update_fun/(:any)'] = 'mantenimiento/funcionario/update_funcionario/$1'; // Update Funcionario
$route['admin/funcionario/add_update_fun'] = 'mantenimiento/funcionario/add_update_funcionario'; // valida Update funcionario
$route['admin/funcionario/delete_fun/(:any)'] = 'mantenimiento/funcionario/del_fun/$1';//eliminar funcionario
$route['admin/mantenimiento/get_fun'] = 'mantenimiento/funcionario/mod_funs';//modificar funcionario

$route['admin/funcionario/verif_ci'] = 'mantenimiento/funcionario/verif_ci';//verif ci
$route['admin/funcionario/verif_usuario'] = 'mantenimiento/funcionario/verif_user';//verif user


$route['admin/mod_contra'] = 'mantenimiento/funcionario/nueva_contra';//cambiar sontrase�a funcionario
$route['admin/mods_contras'] = 'mantenimiento/funcionario/mod_cont';//cambiar contrase�a funcionario
//====================roles==================//


$route['rol'] = 'mantenimiento/roles/roles_menu';//menu roloes
$route['rol_op'] = 'mantenimiento/roles/opciones';//menu roles
$route['mod_opc']='mantenimiento/roles/mod_rol';//modificaciones y adiciones eliminar roles
//================escala salarial===============//
$route['escala_salarial']='mantenimiento/escala_salarial/escala_s';//escala salarial
// $route['add_esc']='mantenimiento/escala_salarial/add_esc';// adicion  escala salarial
// $route['del_esc']='mantenimiento/escala_salarial/del_esc';// eliminar escala salarial
$route['admin/escala_salarial_ver']='mantenimiento/escala_salarial/verificar_cod_car';//escala salarial verificar
$route['admin/escala_salarial_add']='mantenimiento/escala_salarial/add_cargo';//escala salarial add
$route['admin/escala_salarial_mod']='mantenimiento/escala_salarial/get_car';//escala salarial mod
$route['admin/escala_salarial_del']='mantenimiento/escala_salarial/del_car';//escala salarial del
//==================cofiguracion===============//
$route['Configuracion']='mantenimiento/configuracion/configuracion_gestion';// configuracion
$route['Configuracion_mod']='mantenimiento/configuracion/mod_conf';// configuracion modificar a�o
$route['Configuracion_mod_mes']='mantenimiento/configuracion/mod_conf_mes';// configuracion modificar mes
//===================estructura organizacional======================//
$route['estructura_org']='mantenimiento/estructura_organizacional/estruc_org';// vista estructura organizacional
$route['admin/estructura_org_verificar']='mantenimiento/estructura_organizacional/verificar_cod_uni';// vista estructura organizacional verificar codigo
$route['admin/estructura_org_add']='mantenimiento/estructura_organizacional/ad_estructuraorganizacional';// vista estructura organizacional add
$route['admin/estructura_org_mod']='mantenimiento/estructura_organizacional/get_uni';// vista estructura organizacional mod
$route['admin/estructura_org_del']='mantenimiento/estructura_organizacional/del_uni';// vista estructura organizacional eliminar
$route['add_estructura_org']='mantenimiento/estructura_organizacional/add_estructura_org';// adicionar estructura organizacional
//===================partidas==========================//
$route['partidas']='mantenimiento/partidas/lista_partidas';// vista partidas
$route['admin/verificar_par']='mantenimiento/partidas/verificar_cod_par';// vista partidas verificar codigo partida
$route['admin/partidas_add']='mantenimiento/partidas/add_par';// vista partidas adicionar partidas
$route['admin/partidas_mod']='mantenimiento/partidas/get_par';// vista partidas modificar partidas
$route['admin/partidas_del']='mantenimiento/partidas/del_par';// vista partidas eliminar partidas
//===================organismo financiador============//
$route['organismo_financiador']='mantenimiento/organismo_fin/lista_organismo_fin';// vista organismo  financiador
$route['admin/organismo_fin_verif']='mantenimiento/organismo_fin/verificar_cod_of';// vista organismo  financiador verificar codigo
$route['admin/organismo_fin_add']='mantenimiento/organismo_fin/add_organismofinanciador';// vista organismo  financiador add
$route['admin/organismo_fin_mod']='mantenimiento/organismo_fin/get_of';// vista organismo  financiador mod
$route['admin/organismo_fin_del']='mantenimiento/organismo_fin/del_organismofinanciador';// vista organismo  financiador mod
//============================fuente financiamiento=============================//
$route['fuente_financiamiento']='mantenimiento/fuente_financiamiento/lista_fuente_fin';// vista fuente financiamiento
$route['admin/fuente_financiamiento_verif']='mantenimiento/fuente_financiamiento/verificar_cod_ff';// vista fuente financiamiento verificar
$route['fuente_financiamiento_add']='mantenimiento/fuente_financiamiento/add_ff';// vista fuente financiamiento add
$route['fuente_financiamiento_mod']='mantenimiento/fuente_financiamiento/get_ff';// vista fuente financiamiento mod
$route['fuente_financiamiento_del']='mantenimiento/fuente_financiamiento/del_ff';// vista fuente financiamiento mod
//================================entidad de transferencia=====================//
$route['entidad_transferencia']='mantenimiento/entidad_transferencia/lista_enditada_transferencia';// vista entidad trasferencia
$route['admin/entidad_transferencia_ver']='mantenimiento/entidad_transferencia/verificar_cod_et';// vista entidad trasferencia verificar
$route['admin/entidad_transferencia_add']='mantenimiento/entidad_transferencia/add_et';// vista entidad trasferencia add
$route['admin/entidad_transferencia_mod']='mantenimiento/entidad_transferencia/get_et';// vista entidad trasferencia mod
$route['admin/entidad_transferencia_del']='mantenimiento/entidad_transferencia/del_et';// vista entidad trasferencia del
//==================================pdes====================================//
$route['pdes']='mantenimiento/pdes/lista_pdes';// vista pdes
$route['pdes_verificar']='mantenimiento/pdes/verificar_pdes';// verificar pdes
$route['pdes_add']='mantenimiento/pdes/add_pdes_pilar';// add pdes 
$route['pdes_mostrar']='mantenimiento/pdes/mostrar_pilar';// mostrar pdes pilar
$route['pdes_mod_pilar']='mantenimiento/pdes/mod_pilar_pdes';// mostrar pdes pilar
//=================================ptdi=====================================//
$route['ptdi']='mantenimiento/ptdi/lista_ptdi';// vista ptdi


//============================dictaamen=========================//
$route['admin/dictamen_proyecto/(:any)']='reportes/dictamen/pdf_dictamen_proyecto/$1/$2';// dictamen
//==============================correo===============================//
$route['admin/correo']='mantenimiento/entidad_transferencia/enviar';// correo
//pruebas
$route['admin/reportes/objess'] = 'reportes/reporte/prueba';
///////////////////////////programacion//////////////////////
//========================mision y vision=========================//
$route['mision'] = 'programacion/mision/vista_mision';
$route['vision'] = 'programacion/vision/vista_vision';
//======================cambiar gestion=================//
$route['cambiar_gestion'] = 'mantenimiento/cambiar_gestion/listar_c_gestion';//vista de cambiar gestion
$route['cambiar'] = 'nueva_session/cambiar_gestion';//cambiar contralador


//==============Reportes Para Red De Objetivos===========================//
$route['admin/objetivos_estrategicos/(:any)'] = 'reportes/red_objetivos/objetivo_estrategico/$1';
$route['admin/objetivos_gestion/(:any)'] = 'reportes/red_objetivos/objetivo_gestion/$1/$2';
$route['admin/producto_terminal/(:any)'] = 'reportes/red_objetivos/producto_terminal/$1/$2/$3';
$route['admin/reporte_red_obj/(:any)'] = 'reportes/red_objetivos/reporte_red_objetivos/$1/$2';
//=============REPORTES/FRM. POA PLANIFICACION=================//
$route['admin/rep/frm_poa_ejec'] = 'reportes/reportes_vistas/objetivo_estrategicos/2'; //// ----------------- a eliminar
$route['admin/rep/pdf/obj_ac'] = 'reportes/reporte/ficha_tecnica_ejecucion';
//Mantenimiento Clasificacion Sectorial
$route['clasificacion_sectorial'] = 'mantenimiento/clasificacion_sectorial/lista_clasificadores_sectores';
$route['subsector/(:any)'] = 'mantenimiento/clasificacion_sectorial/lista_clasificadores_subsectores/$1/$2';

//$route['admin/reporte/obje'] = 'reportes/reportes_vistas/objetivo_estrategicos/1'; //// ----------------- a eliminar
$route['admin/analisis_sit'] = 'programacion/analisis_situacion';
$route['admin/prog/analisis/(:any)'] = 'programacion/analisis_situacion/ver_analisis_situacion/$1';

$route['trabajando'] = 'trabajando/vista';
$route['admin/reporte/objges_eje'] = 'reportes/reportes_vistas/objetivo_gestion_producto_terminal_ejecutado';

/*===================================== MODULO DE REPORTES ==============================*/
/*--------------------------- REPORTES GERENCIALES ---------------------------*/
$route['rep/resumen_operaciones'] = 'reportes_cns/rep_operaciones/list_regiones'; ///// Resumen de Operaciones y Presupuesto por Unidad Ejecutora
$route['rep/reporte_operaciones/(:any)'] = 'reportes_cns/rep_operaciones/reporte_operaciones/$1'; ///// reporte de todas las operaciones

$route['rep/list_operaciones_req'] = 'reportes_cns/exporting_datos/list_regiones'; ///// Lista de Regiones
$route['rep/list_operaciones/(:any)'] = 'reportes_cns/exporting_datos/mis_operaciones/$1'; ///// Lista Operaciones por regiones
$route['rep/exportar_requerimientos/(:any)'] = 'reportes_cns/exporting_datos/requerimientos/$1'; ///// Exportar Requerimientos
$route['rep/exportar_requerimientos_proceso/(:any)'] = 'reportes_cns/exporting_datos/exporting_requerimientos/$1/$2'; ///// Exportar Requerimientos por Procesos


/*--------------------------- FORMULARIOS POA ---------------------------*/
$route['admin/rep/prog'] = 'reportes/reporte_fpoa/accion_programas'; ///// Lista de Programas para acciones
$route['admin/rep/operaciones/(:any)'] = 'reportes/reporte_fpoa/operaciones/$1/$2'; ///// Lista de Operaciones
$route['admin/reporte/reporte_acciones/(:any)'] = 'reportes/reporte_fpoa/reporte_acciones/$1/$2'; //// Reporte Operaciones //// Programas Padres
$route['admin/reporte/reporte_operaciones_prod/(:any)'] = 'reportes/reporte_fpoa/reporte_por_operaciones_productos/$1/$2'; //// Reporte Operaciones a nivel productos
$route['admin/reporte/reporte_operaciones_act/(:any)'] = 'reportes/reporte_fpoa/reporte_por_operaciones_actividades/$1/$2'; //// Reporte Operaciones a nivel de actividades
$route['admin/reporte/imprimir_operacion/(:any)'] = 'reportes/reporte_fpoa/imprimir_reporte_operacion/$1/$2'; //// Reporte Operaciones //// Proyectos Programas, Operaciones