<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "user";
$route['404_override'] = '';

$route['admin/logout'] = 'user/logout';
$route['admin/validate'] = 'user/validate_credentials';
$route['admin/dashboard'] = 'user/dashboard_index';
$route['admin/dm/(:any)'] = 'user/dashboard_menu/$1';
$route['cambiar_session'] = 'user/cambiar_gestion';//cambiar contralador
$route['cambiar_session_trimestre'] = 'user/cambiar_trimestre';//cambiar trimestre

/*PROGRAMACION*/
$route['admin/combo_ubicacion'] = 'user/combo_ubicacion';
$route['admin/combo_fase_etapas'] = 'user/combo_fases_etapas';
$route['admin/combo_clasificador'] = 'user/combo_clasificador';


/*----------PROGRAMACION ESTRATEGICA------------*/
$route['admin/pei/mision'] = 'user/mision';		///// Mision Institucional
$route['admin/pei/mision/accion/(:any)'] = 'user/pei_accion/$1';	//// Pei Accion
$route['admin/pei/vision'] = 'user/vision';		///// Vision Institucional
$route['admin/pei/ayuda/acerca'] = 'user/acerca';	//// Ayuda

/* MARCO ESTRATEGICO */
/*--------------------------------------- Objetivos Estrategicos ----------------------------------------------*/
$route['me/objetivos_estrategicos'] = 'mestrategico/cobjetivos_estrategico/objetivos_estrategicos';	//// Lista Objetivos Estrategicos
$route['me/valida_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/valida_objetivos_estrategicos';	//// Valida Objetivos Estrategicos
$route['me/get_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/get_objetivos_estrategicos';	//// get Objetivos Estrategicos
$route['me/get_resultado_final'] = 'mestrategico/cobjetivos_estrategico/get_resultado_final';	//// get Resultado Final
$route['me/update_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/update_objetivos_estrategicos';	//// Valida Objetivos Estrategicos
$route['me/delete_objetivo_estrategico'] = 'mestrategico/cobjetivos_estrategico/delete_objetivos_estrategicos';	//// Delete Objetivos Estrategicos

$route['me/reporte_objetivos_estrategicos/(:any)'] = 'mestrategico/cobjetivos_estrategico/reporte_objetivos_estrategicos/$1';	//// Reporte Lista Objetivos Estrategicos
$route['me/reporte_obj/(:any)'] = 'mestrategico/cobjetivos_estrategico/reporte_vinculacion/$1';	//// Reporte Vinculacion PEI
$route['me/exportar_alineacion/(:any)'] = 'mestrategico/cobjetivos_estrategico/exportar_alineacion/$1';	//// Excel Vinculacion PEI

/*--------------------------------------- Acciones Estrategicas ----------------------------------------------*/
$route['me/acciones_estrategicas/(:any)'] = 'mestrategico/cacciones_estrategicas/acciones_estrategicas/$1';	//// Lista Acciones Estrategicas
$route['me/valida_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/valida_acciones_estrategicas';	//// Valida Acciones Estrategicos
$route['me/get_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/get_acciones_estrategicas';	//// get Acciones Estrategicas
$route['me/update_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/update_acciones_estrategicas';	//// Valida Update Acciones Estrategicas
$route['me/delete_acciones_estrategicas'] = 'mestrategico/cacciones_estrategicas/delete_acciones_estrategicas';	//// Delete Acciones Estrategicos

/*--------------------------------------- Resultado de Mediano Plazo ----------------------------------------------*/
$route['admin/me/combo_fun_uni'] = 'mestrategico/cresultado_mplazo/combo_funcionario_unidad_organizacional'; ////// Combo Responsable a Unidad Organizacional
$route['me/resultados_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/list_resultado_mediano_plazo/$1';	//// Lista Resultados de Mediano Plazo
$route['me/get_resultado_intermedio'] = 'mestrategico/cresultado_mplazo/get_resultado_intermedio';	//// get Resultado intermedio
$route['me/new_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/new_resultado_mediano_plazo/$1';	//// Nuevo Resultado de Mediano Plazo
$route['me/valida_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/valida_resultado_mediano_plazo';	//// Valida Resultado de Mediano Plazo
$route['me/update_mplazo/(:any)'] = 'mestrategico/cresultado_mplazo/update_resultado_mediano_plazo/$1';	//// Editar Resultado de Mediano Plazo
$route['me/valida_update_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/valida_update_resultado_mediano_plazo';	//// Valida Update Resultado de Mediano Plazo
$route['me/rep_rmplazo/(:any)'] = 'mestrategico/cresultado_mplazo/reporte_resultado_mediano_plazo/$1';	//// Reporte Resultado de Mediano Plazo
$route['me/delete_resultado_mplazo'] = 'mestrategico/cresultado_mplazo/delete_resultado_mplazo';	//// Delete Resultado de Mediano Plazo

$route['me/get_indicador'] = 'mestrategico/cresultado_mplazo/get_indicador';	//// get Indicador

/* OBJETIVOS DE GESTION INSTITUCIONAL SEGUN ACCION ESTRATEGICA */
$route['me/objetivos_gestion/(:any)'] = 'mestrategico/cobjetivo_gestion/objetivos_gestion/$1'; //// Lista Objetivos de Gestion segun accion estrategica
$route['me/rep_objetivos_gestion/(:any)'] = 'mestrategico/cobjetivo_gestion/reporte_objetivos_gestion/$1'; //// Reporte Objetivo de Gestion

/* OBJETIVOS DE GESTION INSTITUCIONAL */
$route['me/mis_ogestion'] = 'mestrategico/cobjetivo_gestion/list_objetivos_gestion'; //// Lista Objetivos de Gestion General
$route['me/rep_ogestion'] = 'mestrategico/cobjetivo_gestion/reporte_ogestion'; //// Reporte Acciones de Corto Plazo Form 1 2022
//$route['me/rep_ogestion_mes'] = 'mestrategico/cobjetivo_gestion/reporte_ogestion_mes'; //// Reporte Objetivo de Gestion General Msnual
$route['me/alineacion_ope_acp/(:any)'] = 'mestrategico/cobjetivo_gestion/rep_alineacion_acp_act/$1'; ///// ver Alineacion completa de actividades a operaciones y acciones 2022
$route['me/exportar_alineacion_ope_acp/(:any)'] = 'mestrategico/cobjetivo_gestion/exportar_alineacion_acp_act/$1'; ///// Exportar Alineacion completa de actividades a operaciones y acciones 2022
$route['me/reporte_alineacion_ope_acp/(:any)'] = 'mestrategico/cobjetivo_gestion/reporte_alineacion_acp_act/$1'; ///// Reporte Alineacion completa de actividades a operaciones y acciones 2022 INSTITUCIONAL
//$route['me/reporte_alineacion_ope_acp_regional/(:any)'] = 'mestrategico/cobjetivo_gestion/reporte_alineacion_acp_act_regional/$1/$2'; ///// Reporte Alineacion completa de actividades a operaciones y acciones 2022 REGIONAL

/* ---- OBJETIVOS REGIONALES 2022 ---- */
$route['me/objetivos_regionales/(:any)'] = 'mestrategico/cobjetivo_regional/objetivos_regional/$1'; //// Lista Objetivos regional
$route['me/new_oregional/(:any)'] = 'mestrategico/cobjetivo_regional/form_oregional/$1/$2'; //// Nuevo Objetivo Regional
$route['me/update_oregional/(:any)'] = 'mestrategico/cobjetivo_regional/form_update_oregional/$1'; //// Modificar Objetivo Regional
$route['me/combo_oregional'] = 'mestrategico/cobjetivo_regional/combo_oregional'; ////// Combo Objetivos Regionales

$route['me/rep_oregionales/(:any)'] = 'mestrategico/cobjetivo_regional/reporte_objetivos_regionales/$1'; //// Reporte consolidado de Objetivos Regionales
$route['me/rep_form2/(:any)'] = 'mestrategico/cobjetivo_regional/reporte_form2/$1'; ///// ver datos de la regional alineado al objetivo de Gestion form 2 2022

//$route['me/objetivos_regionales/(:any)'] = 'mestrategico/cobjetivo_regional/rep_alineacion_acp/$1'; ///// ver datos de la regional alineado al objetivo de Gestion form 2 2022


/* ANALISIS DE SITUACION */
$route['admin/analisis_sit'] = 'analisis_situacion/canalisis_situacion/lista_unidades';	//// Lista Unidades, Establecimientos
$route['as/list_foda/(:any)'] = 'analisis_situacion/canalisis_situacion/lista_foda/$1';	//// Lista de formularios
$route['as/rep_list_foda/(:any)'] = 'analisis_situacion/canalisis_situacion/reporte_lista_foda/$1';	//// Imprimir lisat Foda

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


////////////////////////////////////// PROGRAMACION POA ////////////////////////////////////
$route['admin/proy/combo_distrital'] = 'programacion/proyecto/combo_distrital'; ////// Combo distrital
$route['admin/proy/combo_administrativas'] = 'programacion/proyecto/combo_da'; ////// Combo Unidades Administrativas
$route['admin/proy/combo_uejecutoras'] = 'programacion/proyecto/combo_ue'; ////// Combo Unidades Ejecutoras
$route['admin/proy/list_proy'] = 'programacion/proyecto/list_proyectos';  //// lista de proyectos 
$route['proy/add_unidad'] = 'programacion/proyecto/form_poa_unidades'; //// formularios de registro - Unidad/Establecimientos
$route['admin/proy/proyecto'] = 'programacion/proyecto/form_proy_inv'; //// formularios de registro - proyectos de Inversion
$route['admin/proy/proyecto_pi/(:any)'] = 'programacion/proyecto/form_operacion_resumen/$1'; //// Proyecto de Inversion

$route['admin/proy/verif'] = 'programacion/proyecto/verif'; ////// verificando datos para la apertura programatica

$route['admin/proy/delete/(:any)'] = 'programacion/proyecto/delete_proyecto/$1/$2'; ////// eliminar proyectos

$route['proy/update_unidad/(:any)'] = 'programacion/proyecto/form_update_poa_unidades/$1'; //// formularios de registro - Unidad/Establecimientos
$route['admin/proy/edit/(:any)'] = 'programacion/proyecto/edit_operacion/$1'; //// ruta de formularios para el editado
$route['proy/presentacion/(:any)'] = 'programacion/proyecto/presentacion_poa/$1'; //// Presentacion de Unidad, Establecimiento
$route['proy/datos_generales_pi/(:any)'] = 'programacion/proyecto/datos_generales_pi/$1'; //// Datos Generales Proyecto de Inversión


$route['admin/combo_unidad'] = 'proyecto/combo_unidad';
$route['admin/proy/list_proy_ok'] = 'programacion/proyecto/list_proyectos_aprobados';  //// lista de proyectos aprobados

$route['prog/update_insumos/(:any)'] = 'programacion/crequerimiento/update_id_requerimientos_pi/$1';//// Actualiza los id de los insumos llevandolos a productos 2020

//// REPORTE PROGRAMACION POA 
///=== Reporte Consolidado POA 2021
$route['rep/list_operaciones_req'] = 'reportes_cns/rep_operaciones/list_regiones'; ///// Menu Lista de Regiones Consolidado POA (2020-2021)
$route['rep/get_uadministrativas'] = 'reportes_cns/rep_operaciones/get_unidades_administrativas'; ////// Combo Unidades Administrativas (CONSOLIDADO REPORTES 2020-2021)
//$route['rep/exportar_operaciones_regional/(:any)'] = 'reportes_cns/exporting_datos/operaciones_regional_nacional/$1/$2'; ///// Exportar Operaciones por regional 
$route['rep/exportar_operaciones_distrital/(:any)'] = 'reportes_cns/exporting_datos/operaciones_distrital/$1/$2'; ///// Exportar Operaciones por distrital 2020-2021
//$route['rep/exportar_operaciones_unidad/(:any)'] = 'reportes_cns/exporting_datos/operaciones_unidad/$1'; ///// Exportar Operaciones por Unidad

//$route['rep/ver_consolidado_operaciones/(:any)'] = 'reportes_cns/exporting_datos/operaciones_nacional/$1'; ///// Exportar Actividades,Operaciones Nacional (Consolidado)

$route['rep/exportar_plantilla_migracion/(:any)'] = 'reportes_cns/exporting_datosmigracion/exportar_planilla_migracion_poa/$1/$2'; ///// Exportar Actividades,Requerimientos hacia plantilla de Migracion 2021



//$route['rep/exportar_requerimientos_regional/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_regional/$1/$2'; ///// Exportar Requerimientos por regional
$route['rep/exportar_requerimientos_distrital/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_distrital/$1/$2'; ///// Exportar Requerimientos por Distrital 2020-2021
$route['rep/exportar_requerimientos_regional/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_regional/$1/$2'; ///// Exportar Requerimientos por Regional 2020-2021
//$route['rep/xls_unidades/(:any)'] = 'reportes_cns/exporting_datos/mis_unidades_pinversion/$1'; ///// Lista de Unidades, Establecimientos de la regional
//$route['rep/ver_consolidado_requerimientos/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_consolidado/$1'; ///// Exportar Requerimientos Nacional (Consolidado)

$route['rep/exportar_poa_oregional/(:any)']='reportes_cns/exporting_datos/ver_poa_oregional_distrital/$1/$2';// Consolidado operaciones por Objetivo Regional 2020-2021 (Distrital)

//$route['rep/exportar_requerimientos_unidad/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_unidad/$1'; ///// Exportar Operaciones por Unidad
$route['rep/exportar_requerimientos_servicio/(:any)'] = 'reportes_cns/exporting_datos/requerimientos_servicio/$1'; ///// Exportar Requerimientos por Servicio (Ejecucion Presupuestaria) 2020-2021 (Excel)
$route['rep/rep_requerimientos_ejecucion_servicio/(:any)'] = 'reportes_cns/exporting_datos/rep_ejecucion_requerimientos_servicio/$1'; ///// Imprimir Requerimientos por Servicio (Ejecucion Presupuestaria) 2020-2021

//$route['rep/ver_partida_uni/(:any)'] = 'reportes_cns/exporting_datos/consolidado_partidas_unidad/$1/$2'; ///// Exportar Consolidado de partidas de cada unidad/Establecimiento por REGIONAL
//$route['rep/ver_partida_consolidado_uni/(:any)'] = 'reportes_cns/exporting_datos/consolidado_nacional_partidas_unidad/$1'; ///// Exportar Consolidado Nacional de partidas de cada unidad/Establecimiento por REGIONAL

$route['rep/comparativo_unidad_ppto/(:any)'] = 'reportes_cns/rep_operaciones/comparativo_presupuesto_distrital/$1/$2'; ///// cuadro comparativo pto asig. poa - Partidas por unidad (DISTRITAL) 2020-2021
$route['rep/establecimientos/(:any)'] = 'reportes_cns/rep_operaciones/establecimientos_salud/$1'; ///// cuadro Establecimientos de salud (DISTRITAL) 2020-2021
$route['rep/print_modificaciones_poa/(:any)'] = 'reportes_cns/rep_operaciones/rep_cuadro_modificacion_poa/$1/$2'; ///// cuadro Modificacion poa (DISTRITAL) 2020-2021
$route['rep/comparativo_xsl/(:any)'] = 'reportes_cns/exporting_datos/comparativo_presupuesto_xls/$1/$2'; ///// cuadro comparativo pto asig. poa - Partidas Xcel

$route['rep/print_certificaciones_poa/(:any)'] = 'reportes_cns/rep_operaciones/rep_cuadro_certificaciones_poa/$1/$2'; ///// cuadro Certificaciones poa (DISTRITAL) 2020-2021

///////// PROGRAMACION FISICA FINANCIERA
/*-----------------------------  PROGRAMACION FISICA DEL PROYECTO - COMPONENTES (2020) -------------------------------*/
$route['prog/list_serv/(:any)'] = 'programacion/cservicios/verif_tipo_ope/$1';  //// Listado de Servicios (2020)
$route['admin/prog/subir_archivo_producto'] = 'programacion/cservicios/archivo_productos'; //// Migracion de Operaciones por servicio
$route['prog/delete_operaciones_componente'] = 'programacion/cservicios/elimina_operaciones_componente';	//// Delete servicio y operaciones
$route['prog/des_sactividad'] = 'programacion/cservicios/deshabilitar_sactividad';	//// Deshabilitar servicio


$route['prog/valida_comp'] = 'programacion/cservicios/valida_componente';  //// Valida Componente (PROY INVERSION)
$route['prog/valida_update_comp'] = 'programacion/cservicios/valida_update_componente';  //// Valida update Componente (PROY INVERSION)
$route['prog/delete_operaciones_componente_pi'] = 'programacion/componente/elimina_operaciones_componente_pi';	//// Delete componente y operaciones Actividades PI


/*-------------------  REPORTE DEL POA Programacion - Fisica y Financiera (2020) ------------------*/
$route['prog/presentacion_poa/(:any)'] = 'programacion/creporte/presentacion_poa/$1';  //// Presentacion POA
$route['prog/reporte_datos/(:any)'] = 'programacion/creporte/datos_generales/$1';  //// Identificacion del POA
$route['prog/rep_operaciones/(:any)'] = 'programacion/creporte/programacion_fisica/$1';  //// Programacion Fisica
$route['prog/rep_requerimientos/(:any)'] = 'programacion/creporte/programacion_financiera/$1';  //// Programacion Financiera
$route['proy/orequerimiento_proceso/(:any)'] = 'programacion/creporte/reporte_formulario5/$1';  //// Reporte requerimiento por Servicios 2020
$route['proy/ptto_consolidado/(:any)'] = 'programacion/creporte/reporte_presupuesto_consolidado/$1';  //// Reporte requerimiento total Unidad/Establecimiento/Proyecto de Inversion
$route['proy/ptto_consolidado_comparativo/(:any)'] = 'programacion/cppto_comparativo/reporte_presupuesto_consolidado_comparativo/$1';  //// Reporte Comparativo Total de Ppto Unidad/Establecimiento/Proyecto de Inversion



/*-----------------------------  PROGRAMACION DEL PROYECTO - PRODUCTOS  -------------------------------*/
$route['admin/prog/list_prod/(:any)'] = 'programacion/producto/lista_productos/$1';  //// lista de productos (2020)
//$route['prog/verif_cod'] = 'programacion/producto/verif_codigo'; ////// verificando codigo Operacion
$route['prog/delete_insumos_servicio/(:any)'] = 'programacion/producto/delete_insumos_servicios/$1';// Eliminar Todas los requerimientos del servicio (2020)
//$route['prog/delete_requerimientos/(:any)'] = 'programacion/producto/delete_requerimientos/$1';// Eliminar Todas los Requerimientos (2020)
$route['admin/prog/valida_upload_prod'] = 'programacion/producto/subir_producto';  //// Subir Archivo Productos txt
//$route['admin/prog/plist_prod/(:any)'] = 'programacion/producto/pre_lista_productos/$1/$2/$3/$4/$5';  //// pre lista de Productos
//$route['admin/prog/subir_prod/(:any)'] = 'programacion/producto/validar_pre_lista_productos/$1/$2/$3/$4/$5';  //// Validar pre lista de Productos
$route['prog/delete_prod'] = 'programacion/producto/delete_operacion';  //// Eliminar Operaciones - Proyecto Inversion

$route['prog/delete_operaciones'] = 'programacion/producto/delete_operaciones'; //// ELIMINA OPERACIONES SELECCIONADOS
$route['prog/combo_acciones'] = 'programacion/producto/combo_acciones_estrategicos';// COMBO ACCIONES OPERATIVAS
$route['prog/rep_operacion_componente/(:any)'] = 'programacion/producto/reporte_operacion_componente/$1';  //// Reporte Operaciones por componente 2019-2020
$route['prog/exportar_productos/(:any)'] = 'programacion/producto/exportar_productos/$1';  //// Exportar lista de Productos
$route['prog/exportar_productos_req/(:any)'] = 'programacion/producto/exportar_productos_requerimientos/$1';  //// Exportar lista de Productos - Requerimientos

$route['prog/update_codigo/(:any)'] = 'programacion/producto/update_codigo/$1';  //// Actualizar Codigos de Operacion

/*-----------------------------  PROGRAMACION DEL PROYECTO - ACTIVIDADES  -------------------------------*/
$route['prog/list_act/(:any)'] = 'programacion/actividades/lista_actividades/$1';  //// lista de actividades
$route['prog/get_actividad'] = 'programacion/actividades/get_actividad';  //// get actividad
$route['prog/valida_act'] = 'programacion/actividades/valida_actividad';  ///// valida Requerimiento
$route['prog/valida_update_act'] = 'programacion/actividades/valida_update_actividad';  ///// valida update Actividad
$route['prog/delete_act'] = 'programacion/actividades/delete_actividad';	//// Elimina Actividad

$route['prog/reporte_form4/(:any)'] = 'programacion/creporte/reporte_formulario4/$1';  //// Reporte Form 4 / 2021
//$route['prog/reporte_form4/(:any)'] = 'programacion/producto/reporte_formulario4/$1';  //// Reporte Form 4 / 2021


/*-----------------------------  PROGRAMACION REQUERIMIENTOS (2020) -------------------------------*/
$route['prog/prog_financiera/(:any)'] = 'programacion/crequerimiento/list_componente/$1';  //// Listado de componentes/operacione, segun el tipo de proyecto
$route['prog/requerimiento/(:any)'] = 'programacion/crequerimiento/list_requerimientos/$1';  //// Listado de Requerimientos
$route['prog/eliminar_insumos_todos/(:any)'] = 'programacion/crequerimiento/eliminar_todos_insumos/$1';  //// eliminar todos los requerimientos

$route['prog/combo_partidas'] = 'programacion/crequerimiento/combo_partidas_hijos';// COMBO PARTIDAS HIJOS
$route['prog/combo_umedida'] = 'programacion/crequerimiento/combo_unidad_medida';// COMBO UNIDAD DE MEDIDA

$route['prog/combo_partidas_asig'] = 'programacion/crequerimiento/combo_partidas_hijos_asignados';// COMBO PARTIDAS ASIGNADOS

$route['prog/list_requerimiento/(:any)'] = 'programacion/cajuste_crequerimiento/list_requerimientos_total/$1';  //// Listado de Requerimientos


/* ==================================== MANTENIMIENTO ====================================*/
$route['mnt/prog_p'] = 'mantenimiento/capertura_programatica/main_apertura_programatica_padres';
/*--- Aperturas Programaticas Hijas -----------*/
$route['mnt/aper_prog'] = 'mantenimiento/capertura_programatica/main_apertura_programatica';
$route['mnt/report_aper_prog'] = 'mantenimiento/capertura_programatica/reporte_apertura_programatica'; ///// Reporte
//analisis ver si habra abm de las aperturas
$route['admin/mantenimiento/add_aper'] = 'cmantenimiento/add_aper';
$route['admin/mantenimiento/get_aper'] = 'cmantenimiento/get_aper';
$route['admin/mantenimiento/del_aper'] = 'cmantenimiento/del_aper';
/*-- Carpetas POA --*/
//$route['mnt/poa'] = 'mantenimiento/cpoa';



/*-- Ptto Sipeg (MANTENIMIENTO)--*/
$route['ptto_asig_poa'] = 'mantenimiento/cptto_poa/list_acciones_operativas'; /// Lista de Acciones Operativas
$route['mnt/rep_partidas/(:any)'] = 'mantenimiento/cptto_poa/rep_partida/$1'; /// Reporte Partida
$route['mnt/edit_ptto_asig/(:any)'] = 'mantenimiento/cptto_poa/edit_partidas/$1'; /// Modifica Monto Partida
$route['mnt/delete_partida'] = 'mantenimiento/cptto_poa/delete_partida';	//// Delete Partida
$route['mnt/ver_ptto_asig_final/(:any)'] = 'mantenimiento/cptto_poa/ver_comparativo_partidas/$1'; /// Modifica Monto Partida
$route['mnt/rep_mod_req/(:any)'] = 'mantenimiento/cptto_poa/reporte_comparativo_unidad/$1';///// Reporte Cuadro Comparativo de Partidas Asig-Prog-Final
$route['mnt/xles_partidas/(:any)'] = 'mantenimiento/cptto_poa/exportar_cuadro_comparativo/$1/$2';///// Exportar cuadro Comparativo de Partidas Asig-Prog-Final en Excel

/*-- Control Operativo (MANTENIMIENTO) --*/
$route['control_calidad'] = 'mantenimiento/ccontrol_calidad/control_calidad'; /// Control de Calidad
$route['select_control_calidad/(:any)'] = 'mantenimiento/ccontrol_calidad/select_control_calidad/$1'; /// tipo de Control de Calidad
$route['list_requerimientos'] = 'mantenimiento/ccontrol_calidad/list_requerimientos'; /// Liat de requerimientos
$route['exportar_requerimientos'] = 'mantenimiento/ccontrol_calidad/exportar_requerimientos'; /// Exportar requerimientos - Control de Calidad

/// REQUERIMIENTOS A NIVEL DE ACTIVIDADES (2020 - GASTO CORRIENTE)
$route['prog/ins_prod/(:any)'] = 'insumos/cprog_insumo/prog_isumos_prod/$1';//PROGRAMACION DE INSUMOS A NIVEL PRODUCTOS
//$route['prog/delete_ins_ope'] = 'insumos/cprog_insumo/delete_get_requerimiento';//// DELETE REQUERIMIENTO 7 ope/act
$route['prog/mod_ins_p/(:any)'] = 'insumos/cprog_insumo/mod_insumo/$1/$2/$3/$4/$5/$6';//MODIFICAR INSUMO A NIVEL PRODUCTO
//$route['prog/delete_ins_p/(:any)'] = 'insumos/cprog_insumo/eliminar_insumos/$1/$2';//ELIMINAR INSUMOS DE LA ACTIVIDAD (2020)
//$route['prog/rep_requerimientos_ope/(:any)'] = 'insumos/cprog_insumo/reporte_requerimientos_operacion/$1';//REQUERIMIENTOS DE LA OPERACI&Oacute;N PDF
$route['prog/rep_partidas_ope/(:any)'] = 'insumos/cprog_insumo/reporte_partida/$1';//Consolidado partidas pdf
$route['prog/xcel_partidas_ope/(:any)'] = 'insumos/cprog_insumo/xcel_reporte_partida/$1';//Consolidado partidas Excel

$route['proy/proceso_productos_consolidado/(:any)'] = 'programacion/componente/reporte_consolidado_operaciones_componentes/$1';  //// Reporte Procesos-Operaciones Consolidado TOTAL

/*---- REGISTRO PROGRAMACION UNIDAD ORGANIZACIONAL (2020) -----*/
$route['prog/unidad'] = 'programacion/cunidad_organizacional/list_unidad'; //// lista Unidad Organizacional
$route['prog/datos_unidad/(:any)'] = 'programacion/cunidad_organizacional/formulario/$1'; //// formulario de datos de la unidad
$route['prog/combo_ubicacion'] = 'programacion/cunidad_organizacional/combo_ubicacion'; ///// Lista de Municipios
$route['prog/rep_datos_unidad/(:any)'] = 'programacion/cunidad_organizacional/reporte_datos_unidad/$1'; //// Reporte Datos Unidad

$route['prog/rep_list_establecimientos/(:any)'] = 'programacion/cunidad_organizacional/rep_list_establecimientos/$1'; //// lista de Establecimientos Habilitados por Regional
$route['prog/rep_consolidado_establecimientos'] = 'programacion/cunidad_organizacional/rep_consolidado_establecimientos'; //// lista Consolidado de Establecimientos PDF
$route['prog/rep_consolidado_establecimientos_xls'] = 'programacion/cunidad_organizacional/rep_consolidado_establecimientos_xls'; //// lista Consolidado de Establecimientos EXCEL

$route['prog/c_servicio'] = 'programacion/cunidad_organizacional/list_scompra'; //// lista Servicio de Compra
$route['prog/rep_list_cservicio/(:any)'] = 'programacion/cunidad_organizacional/rep_list_cservicio/$1'; //// lista de Compra de Servicios
$route['prog/rep_consolidado_cservicio'] = 'programacion/cunidad_organizacional/rep_consolidado_cservicio'; //// lista Consolidado de Establecimientos PDF
$route['prog/rep_consolidado_cservicio_xls'] = 'programacion/cunidad_organizacional/rep_consolidado_cservicio_xls'; //// lista Consolidado de Establecimientos EXCEL

$route['proy/verif_plantillas'] = 'insumos/cprog_insumo/verificar_plantilla'; ////// Verificar plantillas de migración

/*-----------------------------  FASES  DEL PROYECTO -------------------------------*/
$route['admin/proy/fase_etapa/(:any)'] = 'programacion/faseetapa/list_fase_etapa/$1';  //// lista fase etapas  - proy_id
$route['admin/proy/newfase/(:any)'] = 'programacion/faseetapa/nueva_fase/$1';  //// nueva fase
$route['admin/proy/fase_ptto/(:any)'] = 'programacion/faseetapa/fase_presupuesto/$1';  //// nueva fase presupuesto
//$route['admin/proy/add_fe'] = 'programacion/faseetapa/add_fase';  //// valida1 fase/etapa
$route['admin/proy/add_fe2'] = 'programacion/faseetapa/add_fase2';  //// valida2 fase/etapa
$route['admin/proy/update_f/(:any)'] = 'programacion/faseetapa/modificar_fase/$1';  //// opcion Modificar Fase
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

$route['admin/proy/update_techo'] = 'programacion/faseetapa/update_techo_ptto';  //// Update datos del techo presupuesto x

$route['admin/proy/actualiza_techo_ptto'] = 'programacion/faseetapa/valida_techo_ptto';  //// ver techo presupuestario de la fase (Actualizando lo ultimo)
$route['admin/proy/delete_recurso/(:any)'] = 'programacion/faseetapa/delete_recurso/$1/$2/$3/$4';  //// Delete datos del techo presupuesto recurso x (Borrar)

$route['proy/get_faseetapa'] = 'programacion/faseetapa/obtiene_faseetapa'; /// Get Fase Activa Proyecto
$route['proy/valida_ptto'] = 'programacion/faseetapa/valida_presupuesto';  ///// valida presupuesto fase
$route['proy/add_ptto_techo'] = 'programacion/faseetapa/validar_techo_ptto';  //// Validar datos del techo presupuesto
$route['proy/delete_asignacion'] = 'programacion/faseetapa/delete_asignacion';  //// Delete asignacion presupuestaria


/*---------- EVALUACION DE LA OPERACION 2020 (GASTO CORRIENTE) ----------*/
$route['eval/mis_operaciones'] = 'ejecucion/cevaluacion/operaciones_aprobadas';  ///// lista de operaciones aprobadas
$route['eval/eval_productos/(:any)'] = 'ejecucion/cevaluacion/mi_evaluacion/$1';  ///// Evaluar Gasto Corriente-Proyecto de Inversion
$route['eval/eval_gcorriente/(:any)'] = 'ejecucion/cevaluacion/evaluar_gastocorriente/$1';  ///// Evaluar Operaciones - Gasto Corriente

$route['eval/rep_eval_productos/(:any)'] = 'ejecucion/cevaluacion/reporte_evaluar_operaciones/$1';  ///// Reporte Evaluacion Productos 2020 al trimestre vigente
$route['eval/rep_eval_productos_trimestral/(:any)'] = 'ejecucion/cevaluacion/reporte_evaluar_operaciones_trimestral/$1/$2';  ///// Reporte Evaluacion Productos 2020 segun el trimestre
$route['eval/rep_eval_productos_consolidado/(:any)'] = 'ejecucion/cevaluacion/reporte_evaluar_operaciones_consolidado/$1';  ///// Reporte Evaluacion Productos Consolidado 2020
$route['eval/reformular/(:any)'] = 'ejecucion/cevaluacion/reformular_evaluacion/$1';  /// REFORMULAR EVALUACION POA

$route['eval/get_productos'] = 'ejecucion/cevaluacion/get_productos'; ///// Get Producto para la Evaluacion
$route['eval/get_mod_productos'] = 'ejecucion/cevaluacion/get_mod_productos'; ///// Get Producto para la Modificacion Evaluacion
$route['eval/valida_eval_prod'] = 'ejecucion/cevaluacion/valida_evaluacion_producto'; ///// valida evaluacion Producto
$route['eval/valida_meval_prod'] = 'ejecucion/cevaluacion/valida_mod_evaluar_productos';  ///// valida mod Evaluar Productos
$route['eval/update_trimestre'] = 'ejecucion/cevaluacion/valida_update_trimestre';  ///// valida update trimestre

$route['eval/get_actividad'] = 'ejecucion/cevaluacion/get_actividad'; ///// Get Actividad
$route['eval/get_mod_actividad'] = 'ejecucion/cevaluacion/get_mod_actividad'; ///// Get actividad para la Modificacion Evaluacion
$route['eval/valida_eval_act'] = 'ejecucion/cevaluacion/valida_evaluacion_actividad'; ///// valida evaluacion Actividad
$route['eval/valida_meval_act'] = 'ejecucion/cevaluacion/valida_mod_evaluar_actividad';  ///// valida mod Evaluar Actividad

/*---------- EVALUACION DE LA OPERACION 2020 (PROYECTO DE INVERSIÓN) ----------*/
$route['eval/eval_pinversion/(:any)'] = 'ejecucion/cevaluacion_pi/evaluar_proyectoinversion/$1';  ///// Evaluar Operaciones - Proyecto de Inversión


/*--- SEGUIMIENTO POA (GASTO CORRIENTE-PROYECTO DE INVERSION 2021) ---*/
$route['seg/seguimiento_poa'] = 'ejecucion/cseguimiento/lista_poa'; ///// lista de operaciones aprobadas
$route['seg/tipo_seguimiento/(:any)'] = 'ejecucion/cseguimiento/tipo_seguimiento_poa/$1'; ///// Tipo de seguimiento POA
$route['seg/formulario_seguimiento_poa/(:any)'] = 'ejecucion/cseguimiento/formulario_segpoa/$1'; ///// formulario de seguimiento
$route['seg/ver_seguimientopoa/(:any)'] = 'ejecucion/cseguimiento/ver_evaluacion_poa/$1'; ///// Ver Evaluacion POA
$route['seg/ver_reporte_seguimientopoa/(:any)'] = 'ejecucion/cseguimiento/ver_reportesegpoa/$1'; ///// Ver Reporte Seguimiento POA Mensual
$route['seg/ver_reporte_evaluacionpoa/(:any)'] = 'ejecucion/cseguimiento/ver_reporteevalpoa/$1/$2'; ///// Ver Reporte Evaluacion POA Trimestral
$route['seg/ver_reporte_evaluacionpoa_temporalidad/(:any)'] = 'ejecucion/cseguimiento/ver_reporteevalpoa_consolidado_temporalidad/$1'; ///// Ver Reporte Evaluacion POA Consolidado de todos los meses

$route['seg/notificacion_operaciones_mensual/(:any)'] = 'ejecucion/cseguimiento/reporte_notificacion_operaciones_mensual/$1'; ///// Reporte Notificacion Seguimiento POA Mensual
$route['seg/reporte_consolidado_seguimientopoa_mensual/(:any)'] = 'ejecucion/cseguimiento/reporte_consolidadopoa_operaciones_mensual/$1/$2'; ///// Reporte formulario Seguimiento POA Mensual

/*--- SEGUIMIENTO EVALUACION POA (GASTO CORRIENTE-PROYECTO DE INVERSION 2021) ---*/
$route['evalpoa/formulario_evaluacion_poa/(:any)'] = 'ejecucion/cevaluacion_poa/formulario_evaluacionpoa/$1'; ///// formulario de Evaluacion POA

//====== REPORTE SEGUIMIENTO EVALUACION POA 2021
$route['rep_seguimiento_poa'] = 'reporte_seguimiento_poa/crep_seguimientopoa/list_regiones';  /// MENU SEGUIMIENTO POA 2021
$route['rep/get_seguimiento_da'] = 'reporte_seguimiento_poa/crep_seguimientopoa/get_unidades_administrativas'; ////// Combo Unidades Administrativas Seguimiento POA
$route['rep/get_reporte_seguimientopoa/(:any)'] = 'reporte_seguimiento_poa/crep_seguimientopoa/reporte_seguimiento_poa_unidades/$1/$2/$3/$4'; ////// Reporte Seguimiento POA por Unidades,Distrital,Regional

//====== MENU SEGUIMIENTO A ESTABLECIMIENTOS DE SALUD 2021
$route['seguimiento_establecimientos'] = 'ejecucion/cseguimiento_establecimiento/formulario_establecimiento';  /// MENU SEGUIMIENTO POA 2021
$route['seg/ver_reporte_evaluacionpoa_es/(:any)'] = 'ejecucion/cseguimiento_establecimiento/ver_reporte_seguimientopoa_esalud/$1'; ///// Ver Reporte Evaluacion POA Mensual

//====== SEGUIMIENTO POA A SUBACTIVIDADES DE LAS AREAS ADMINISTRATIVAS 2021 (MODULO PARA CADA SUBUNIDAD)
$route['seguimiento_poa'] = 'ejecucion/cseguimiento/formulario_subactividad';  /// FORMULARIO SEGUIMIENTO POA - SUBACTIVIDAD
$route['seguimiento_poa/reporte_seguimientopoa_mensual/(:any)'] = 'ejecucion/cseguimiento/reporte_formulario_subactividad_mes/$1/$2';  /// REPORTE FORMULARIO SEGUIMIENTO POA - SUBACTIVIDAD
///Solitiud de Certificacion POA
$route['solicitar_certpoa/(:any)'] = 'ejecucion/ccertificacion_poa/solicitar_certpoa/$1';  /// SOLICITAR CERTIFICACION POA
$route['form_certpoa/(:any)'] = 'ejecucion/ccertificacion_poa/formulario_certpoa/$1';  /// FORMULARIO CERTIFICACION POA
$route['solicitud_poa/(:any)'] = 'ejecucion/ccertificacion_poa/solicitud_certpoa/$1';  /// SOLICITUD CERTIFICACION POA
$route['reporte_solicitud_poa/(:any)'] = 'ejecucion/ccertificacion_poa/reporte_solicitud_certpoa/$1';  /// FORMULARIO SOLICITUD CERTIFICACION POA
$route['mis_solicitudes_cpoa/(:any)'] = 'ejecucion/ccertificacion_poa/mis_solicitudes_certificacionespoa/$1';  /// MIS SOLICITUDES CERTIFICACION POA

/*-- Aprobar Solicitud Certificacion POA ---*/
$route['ejec/mis_solicitudes_certpoa'] = 'ejecucion/ccertificacion_poa/ver_mis_solicitudes_certpoa';  ///// VER MIS SOLICITUDES DE CERTIFICACION POA
$route['reporte_solicitud_poa_aprobado/(:any)'] = 'ejecucion/ccertificacion_poa/reporte_solicitud_probado_certpoa/$1';  /// REPORTE SOLICITUD CERTIFICADO POA (APROBADO)

//====== REPORTE EVALUACION POA 2020
/*---------- EVALUACION POA INSTITUCIONAL 2021 ----------*/
$route['menu_eval_poa'] = 'reporte_evaluacion/crep_evalinstitucional/menu_eval_poa';  /// MENU EVALUACION POA 
$route['rep_eval_poa/iframe_rep_evaluacionpoa/(:any)'] = 'reporte_evaluacion/crep_evalinstitucional/iframe_evaluacion_poa/$1/$2/$3';  /// IFRAME NACIONA, REGIONAL, DISTRITAL 2021


$route['rep_eval_poa/evaluacion_poa_onacional/(:any)'] = 'reporte_evaluacion/crep_evalofinacional/evaluacion_poa_onacional/$1';  /// REPORTES GRAFICOS OFICINA NACIONAL
//$route['rep_eval_poa/rep_eficacia/(:any)'] = 'reporte_evaluacion/crep_evalinstitucional/reporte_parametros/$1/$2';  /// REPORTE EVALUACION GASTO CORRIENTE

/*---------- EVALUACION DE LA OPERACION - UNIDAD, PROYECTO ----------*/
$route['eval/eval_unidad/(:any)'] = 'reporte_evaluacion/crep_evalunidad/evaluacion_poa_unidad/$1';  /// REDIRECCION EVALUACION POA - UNIDAD, PROY INV.
$route['eval/eval_unidad_gcorriente/(:any)'] = 'reporte_evaluacion/crep_evalunidad/evaluacion_unidad_gcorriente/$1';  /// REPORTES GRAFICOS DE EVALUACION GASTO CORRIENTE
$route['rep_eficacia_unidad/(:any)'] = 'reporte_evaluacion/crep_evalunidad/reporte_indicadores_unidad/$1';  /// REPORTE POR EFICIENCIA Y EFICACIA POR CADA UNIDAD O PROYECTO DE UNIDAD

$route['rep_indicadores_unidad/(:any)'] = 'reporte_evaluacion/creportes_evaluacionpoa/reporte_indicadores_unidades/$1/$2/$3';  /// REPORTE INDICADORES POR UNIDAD 2021
$route['rep_indicadores_programa/(:any)'] = 'reporte_evaluacion/creportes_evaluacionpoa/reporte_categoria_programatica/$1/$2/$3';  /// REPORTE POR CATEGORIA PROGRAMATICA 2021


/*---------- EVALUACION GASTO CORRIENTE - PROGRAMA ----------*/
$route['menu_eval_prog'] = 'reporte_evaluacion/crep_evalprogramas/menu_eval_programas';  /// MENU EVALUACION PROGRAMA 
$route['rep_eval_prog/evaluacion_programas/(:any)'] = 'reporte_evaluacion/crep_evalprogramas/evaluacion_programas/$1/$2';  /// REPORTES GRAFICOS REGIONAL


//====== REPORTE EVALUACION OBJETIVOS DE GESTION 2020
$route['menu_eval_objetivos'] = 'reporte_evalobjetivos/crep_evalobjetivos/menu_eval_objetivos';  /// MENU EVALUACION OBJETIVOS 
$route['rep_eval_obj/evaluacion_objetivos/(:any)'] = 'reporte_evalobjetivos/crep_evalobjetivos/evaluacion_objetivos/$1';  /// REPORTES GRAFICOS REGIONAL



/*--------- EJECUCION - CERTIFICACION POA (TUE) 2019 -----------*/
$route['ejec/menu_cpoa'] = 'ejecucion/cert_poa/menu_certificacion_poa'; //// Menu Tecnico de Unidad Ejecutora 2021
$route['ejec/get_uadministrativas'] = 'ejecucion/cert_poa/get_unidades_administrativas'; ////// Combo Unidades Administrativas


/*-- EVALUACION PEI - OBJETIVOS REGIONALES 2020 ---*/
$route['eval_obj/objetivos_regionales'] = 'ejecucion/cevaluacion_pei/objetivos_regionales';  ///// Objetivos Estrategicos
$route['eval_obj/rep_meta_oregional/(:any)'] = 'ejecucion/cevaluacion_pei/reporte_meta_oregional/$1'; //// Reporte Meta Objetivo Regional
$route['eval_obj/rep_meta_oregional_grafico/(:any)'] = 'ejecucion/cevaluacion_pei/cuadro_evaluacion_grafico/$1'; //// Reporte Meta Objetivo Regional Grafico


/*--FORM. CERT---*/
$route['ejec/valida_cpoa1'] = 'ejecucion/cert_poa/valida_cpoa_operacion_requerimiento_form1'; //// Valida Certificacion POA Form1
$route['ejec/cert_poa_form2/(:any)'] = 'ejecucion/cert_poa/formulario_cpoa2/$1/$2/$3'; //// Formulario 2 - Cert POA
$route['ejec/certifica'] = 'ejecucion/cert_poa/valida_cpoa_operacion_requerimiento_certifica'; //// Valida Certificacion POA Form1
/*--------------*/

/*-- CERTIFICACIÓN POA 2020 ---*/
$route['cert/list_poas'] = 'ejecucion/ccertificacion_poa/list_poas_aprobados'; //// Lista POA Aprobados 2020
$route['cert/form_items/(:any)'] = 'ejecucion/ccertificacion_poa/list_items_cert/$1'; //// Lista Items a Certificar 2020
$route['cert/ver_cpoa/(:any)'] = 'ejecucion/ccertificacion_poa/ver_certificacion_poa/$1'; //// Ver Certificacion Modificado POA 2020
$route['cert/ver_cpoa_anulado/(:any)'] = 'ejecucion/ccertificacion_poa/ver_certificacion_poa_anulado/$1'; //// Ver Certificacion Modificado POA 2020
$route['cert/rep_cert_poa/(:any)'] = 'ejecucion/ccertificacion_poa/reporte_cpoa/$1'; //// reporte Certificado POA 2021
$route['cert/rep_cert_poa_editado/(:any)'] = 'ejecucion/ccertificacion_poa/reporte_cpoa_editado/$1'; //// reporte Certificado POA Editado 2021
$route['cert/rep_cert_poa_anulado/(:any)'] = 'ejecucion/ccertificacion_poa/reporte_cpoa_anulado/$1'; //// reporte Certificado POA Anulado 2021
$route['cert/generar_codigo/(:any)'] = 'ejecucion/ccertificacion_poa/generar_codigo/$1'; //// Generar Codigo Cert POA 2020
$route['cert/eliminar_certificacion/(:any)'] = 'ejecucion/cert_poa/eliminar_certificacion/$1'; //// Eliminar Certificacion POA

/*-- EDICION DE CERTIFICACIÓN POA 2020 ---*/
$route['cert/edit_certificacion/(:any)'] = 'ejecucion/ccertificacion_poa/modificar_cpoa/$1'; //// Generar Codigo Cert POA 2020
$route['cert/update_certpoa_insumo/(:any)'] = 'ejecucion/ccertificacion_poa/actualizar_monto_certificado_por_insumo/$1'; //// Actualizando datos de certificacion poa 2021 (Nuevo)


/*----------------------------- EJECUCION - CERTIFICACION POA (POA) -------------------------------*/
$route['ejec/menu_vpoa'] = 'ejecucion/cert_poa/menu_certificaciones'; //// Certificaciones poas VPOA
$route['ejec/rechazar_cert'] = 'ejecucion/cert_poa/rechazar_certificacion';  //// Valida para recchazar la certificacion POA
//$route['ejec/delete_cert'] = 'ejecucion/cert_poa/eliminar_certificacion';  //// Eliminar la certificacion POA

$route['ejec/anular_ref/(:any)'] = 'ejecucion/cert_poa/anular_reformulado/$1';  //// Anular Reformulado
$route['ejec/verificar_reformulacion/(:any)'] = 'ejecucion/cert_poa/ver_requerimientos_nocertificados/$1';  //// Verificar Reformulado

$route['ejec/detalle_ediciones'] = 'ejecucion/cert_poa/reporte_ediciones_cpoas'; //// Reporte Consolidado de ediciones al POA

/*------------ MODIFICACIONES DE OPERACIONES (ULTIMO)--------------*/
$route['mod/ope_aprobadas'] = 'modificaciones/cmodificaciones/operaciones_aprobadas';  ///// lista de operaciones aprobadas
$route['mod/derivar_operacion'] = 'modificaciones/cmodificaciones/derivar_operacion';  ///// derivar Operacion a TOP
$route['mod/list_top'] = 'modificaciones/cmodificaciones/list_poas_aprobados';  ///// Lista de POas Aprobados

$route['mod/list_cites/(:any)'] = 'modificaciones/cmodificaciones/lista_cites/$1';  ///// Lista de Modificaciones POA 

/*------- Modificar Techo - Partidas Asignadas (TECHO PRESUPUESTARIO)-------*/
$route['mod/cite_techo/(:any)'] = 'modificaciones/cmod_requerimientos/cite_techo/$1'; //// Cite Techo 
$route['mod/techo/(:any)'] = 'modificaciones/cmod_requerimientos/techo/$1'; //// Modificar Techo
$route['mod/rep_mod_req/(:any)'] = 'modificaciones/cmod_requerimientos/reporte_modificacion/$1/$2';  ///// Reporte Modificaciones Requerimientos 2019
$route['mod/rep_mod_techo/(:any)'] = 'modificaciones/cmod_requerimientos/reporte_techo/$1';  ///// Reporte Modificaciones Techo 2019
//$route['mod/techo/(:any)'] = 'modificaciones/cmod_requerimientos/techo/$1'; //// Ver Modificacion de  Techo

/*---- MODIFICACIÓN PRESUPUESTARIA 202-2021 -----*/
$route['mod_ppto/list_mod_ppto'] = 'modificaciones/cmod_presupuestario/lista_mod_ppto'; //// Valida Cite Modificacion Presupuestaria
$route['mod_ppto/delete_mod_ppto'] = 'modificaciones/cmod_presupuestario/delete_modificacion_presupuestaria';//// Elimina Modificacion Presupuestaria
$route['mod_ppto/rep_mod_ppto/(:any)'] = 'modificaciones/cmod_presupuestario/reporte_mod_ppto/$1'; //// Reporte Modificacion Presupuestaria
$route['mod_ppto/rep_mod_ppto_distrital/(:any)'] = 'modificaciones/cmod_presupuestario/reporte_mod_ppto_clasificado/$1/$2'; //// Reporte Modificacion Presupuestaria clasificado por distrital
$route['mod_ppto/ver_partidas_mod/(:any)'] = 'modificaciones/cmod_presupuestario/partidas_modificadas/$1'; //// Reporte Modificacion Presupuestaria



/*------------- MODIFICAR REQUERIMIENTOS (2020)------------*/
$route['mod/procesos/(:any)'] = 'modificaciones/cmod_insumo/cite_servicios/$1'; //// Lista cite de Servicios 
$route['mod/list_requerimientos/(:any)'] = 'modificaciones/cmod_insumo/mis_requerimientos/$1'; //// Lista de Requerimientos
$route['mod/del_select_ins/(:any)'] = 'modificaciones/cmod_insumo/elimina_requerimientos_producto_actividad/$1'; //// Elimina Requerimientos Seleccionados (2020)
$route['mod/rep_mod_financiera/(:any)'] = 'modificaciones/cmod_insumo/reporte_modificacion_financiera/$1';  ///// Reporte Modificaciones Financiera 2020
$route['mod/update_cite/(:any)'] = 'modificaciones/cmod_insumo/modificar_cite/$1';  ///// Modificar Cite 
$route['mod/ver_mod_poa/(:any)'] = 'modificaciones/cmod_insumo/ver_modificacion_poa/$1';  ///// Ver Modificacion POA

/*------------- MODIFICAR OPERACIONES (2020-2021)------------*/
$route['mod/list_componentes/(:any)'] = 'modificaciones/cmod_fisica/mis_subactividades/$1'; //// Lista de Subactividades 2020-2021
$route['mod/lista_operaciones/(:any)'] = 'modificaciones/cmod_fisica/list_operaciones/$1'; //// la lista de operaciones 2020
$route['mod/update_ope/(:any)'] = 'modificaciones/cmod_fisica/update_operacion/$1/$2'; //// 
$route['mod/update_codigo/(:any)'] = 'modificaciones/cmod_fisica/update_codigo/$1';  //// Actualizar Codigos de Operacion
$route['mod/reporte_modfis/(:any)'] = 'modificaciones/cmod_fisica/reporte_modificacion_fisica/$1';  ///// Reporte de Modificacion Fisica
$route['mod/ver_mod_poa_fis/(:any)'] = 'modificaciones/cmod_fisica/ver_modificacion_poa/$1';  ///// Ver Modificacion POA (FIS)


/*------------- Consolidado Modificaciones en Excel (2021) -----------------*/
$route['mod/consolidado_mod_requerimiento/(:any)'] = 'modificaciones/crep_modificaciones/consolidado_xls_requerimientos/$1'; //// Consolidado XLS Requerimientos


/*------------------------------------ FUNCIONARIOS --------------------------------------*/
$route['admin/mnt/list_usu'] = 'mantenimiento/funcionario/list_usuarios';  //// lista de usuarios
$route['mnt/rep_list_usu/(:any)'] = 'mantenimiento/funcionario/reporte_list_usuarios/$1';  //// lista de usuarios

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

//----------------------------- CONFIGURACION -------------------------------//
$route['Configuracion']='mantenimiento/cconfiguracion/main_configuracion';// main configuracion
$route['Configuracion_mod']='mantenimiento/configuracion/mod_conf';// configuracion modificar a�o
$route['Configuracion_mod_mes']='mantenimiento/configuracion/mod_conf_mes';// configuracion modificar mes

//--------------------------- MANTENIMIENTO (PRESENTACION POA) ----------------------//
$route['mnt/presentacion_poa']='mantenimiento/cconfiguracion/presentacion_poa';// Presentacion POA - Regionales
$route['mnt/caratula_poa/(:any)']='mantenimiento/cconfiguracion/ver_caratula_poa/$1';// Caratula Gasto Corriente POA
$route['mnt/caratula_pi/(:any)']='mantenimiento/cconfiguracion/ver_caratula_pi/$1';// Caratula Proyecto de Inversion POA

//--------------- ESTRUCTURA ORGANIZACIONAL ---------------//
$route['estructura_org']='mantenimiento/cestructura_organizacional/list_estructura';// Lista de Unidades - Actividades
$route['mnt/verif']='mantenimiento/cestructura_organizacional/verif_actividad_apertura';// Verif Actividad-Apertura
$route['mnt/verif_cod']='mantenimiento/cestructura_organizacional/verif_codigo_actividad';// Verif Codigo Actividad Institucional
$route['mnt/verif_cod_sact']='mantenimiento/cestructura_organizacional/verif_codigo_sub_actividad';// Verif Codigo Sub Actividad
$route['mnt/valida_actividad']='mantenimiento/cestructura_organizacional/valida_actividad';// Valida Actividad
$route['mnt/get_actividad']='mantenimiento/cestructura_organizacional/get_actividad';// get Actividad UO
$route['mnt/get_sub_actividad']='mantenimiento/cestructura_organizacional/get_sub_actividad';// get Sub Actividad UO
$route['mnt/valida_update_actividad']='mantenimiento/cestructura_organizacional/valida_update_actividad';// Valida Update Actividad
$route['mnt/valida_update_sub_actividad']='mantenimiento/cestructura_organizacional/valida_update_sub_actividad';// Valida Update Sub Actividad
$route['mnt/delete_actividad'] = 'mantenimiento/cestructura_organizacional/delete_actividad';	//// Delete Actividad
$route['mnt/rep_estructura/(:any)'] = 'mantenimiento/cestructura_organizacional/reporte_estructura/$1';	//// reporte Estructura Organica

$route['admin/proy/combo_act'] = 'mantenimiento/cestructura_organizacional/combo_act'; ////// Combo Unidades/ Establecimientos

//---------- ALINEAR TIPO DE ESTABLECIMIENTO CON SERVICIO ------------//
$route['tp_establecimientos']='mantenimiento/cestructura_organizacional/list_establecimiento';// Lista de Establecimiento
$route['rep_tp_establecimientos']='mantenimiento/cestructura_organizacional/reporte_list_establecimiento';// Reporte Lista de Establecimiento
$route['servicios/(:any)']='mantenimiento/cestructura_organizacional/list_servicios/$1';// Lista de Servicios

//---------- LISTA DE POAS SCANNEADOS ----------//
$route['mis_poas_scanneados']='mantenimiento/cpoas_scanneados/list_poa_scanneados';// Lista de POAS Escaneados

//============= MANTENIMIENTO PARTIDAS =====================//
$route['partidas']='mantenimiento/partidas/lista_partidas';// vista partidas
$route['imprime_partida']='mantenimiento/partidas/imprime_partidas';// Imprimir lista de partidas
$route['umedidas/(:any)']='mantenimiento/partidas/umedidas/$1';// Lista de Unidades de medida
$route['admin/verificar_par']='mantenimiento/partidas/verificar_cod_par';// vista partidas verificar codigo partida
$route['admin/partidas_add']='mantenimiento/partidas/add_par';// vista partidas adicionar partidas
$route['admin/partidas_mod']='mantenimiento/partidas/get_par';// vista partidas modificar partidas
$route['admin/partidas_del']='mantenimiento/partidas/del_par';// vista partidas eliminar partidas

//--------------------------- CONF. PROYECTOS DE INVERSIÓN ----------------------//
$route['proy_inversion']='mantenimiento/cconf_pinversion/list_proyectos';// Lista Proyectos de Inversion
$route['mnt/activar_fase']='mantenimiento/cconf_pinversion/activar_fase';// Lista Proyectos de Inversion

$route['ver_consolidado/(:any)']='mantenimiento/cconf_pinversion/consolidado_temporalidad/$1/$2';// Consolidado de Temporalidad - Programado,Ejecutado (EVALUACION)

//--------------------------- MANTENIMIENTO EDICIONES ----------------------//
$route['ediciones']='mantenimiento/cediciones/menu_ediciones';// Menu de Ediciones- Certificaciones-Modificaciones
$route['rep_ediciones/(:any)']='mantenimiento/cediciones/rep_ediciones/$1';// Exportar PDF


///////////////////////////programacion//////////////////////
//========================mision y vision=========================//
$route['mision'] = 'programacion/mision/vista_mision';
$route['vision'] = 'programacion/vision/vista_vision';
//======================cambiar gestion=================//
$route['cambiar_gestion'] = 'mantenimiento/cambiar_gestion/listar_c_gestion';//vista de cambiar gestion
$route['cambiar'] = 'nueva_session/cambiar_gestion';//cambiar contralador

$route['trabajando'] = 'trabajando/vista';
$route['error'] = 'trabajando/error';


/*--------------------------- REPORTES - MARCO ESTRATEGICO INSTITUCIONAL ---------------------------*/
$route['rep/ogestion/(:any)'] = 'reportes_cns/crep_ogestion/mis_ogestion/$1'; ///// Lista de Objetivos de Gestion - FORMULARIO SPO 01
$route['rep/regional_ogestion/(:any)'] = 'reportes_cns/crep_ogestion/list_regionales_ogestion/$1'; ///// Lista de Regionales - FORMULARIO SPO 02

/*--------------------------- REPORTES - CONSULTAS INTERNAS ---------------------------*/
$route['consulta/mis_operaciones'] = 'consultas_cns/c_consultas/mis_operaciones'; ///// Mis operaciones
$route['consulta/cambiar'] = 'consultas_cns/c_consultas/cambiar_gestion';//cambiar contralador
$route['rep/get_consultas_da'] = 'consultas_cns/c_consultas/get_opciones'; ////// Combo Unidades Administrativas Seguimiento POA
$route['rep/comparativo_unidad_ppto_regional/(:any)'] = 'consultas_cns/c_consultas/comparativo_presupuesto_regional/$1/$2'; ///// cuadro comparativo pto asig. poa - Partidas por unidad (REGIONAL) 2020-2021
$route['rep/exportar_operaciones_regional/(:any)'] = 'reportes_cns/exporting_datos/operaciones_regional/$1/$2'; ///// Exportar Operaciones por distrital 2020-2021

///////// REPORTE RESUMEN DE ALINEACION ACTIVIDAD A CATEGORIA PROGRAMATICA 2021
$route['rep/resumen_act_programa'] = 'reporte_resumen_alineacion_poa/crep_actprog/regional';  //// Menu Regional act-prog (2020-2021)
$route['rep/rep_alineacion_poa/(:any)'] = 'reporte_resumen_alineacion_poa/crep_actprog/reporte_alineacion_poa/$1';  //// Reporte Alineacion POA (2020-2021)

