<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;

//$db['default']['hostname'] = '172.16.0.54';
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'postgres';
$db['default']['password'] = '123456';
$db['default']['database'] = 'cns08012025';  //// BD_CNS - BASE DE DATOS ORIGINAL CAMBIADO 11/03/2019 (CNS YA NO ES LA ORIGINAL)
//$db['default']['password'] = 'cns51stemas';
//$db['default']['database'] = 'bd_cns'; 

$db['default']['dbdriver'] = 'postgre';
$db['default']['port'] = '5432';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
