<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'auth/login/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/** AUTH (pisah controller) */
$route['masuk']             = 'auth/login/index';     
$route['keluar']            = 'auth/login/logout';    
$route['auth/login']        = 'auth/login/index';

$route['auth/otp']        = 'auth/otp/index';
$route['auth/otp/resend'] = 'auth/otp/resend';

$route['daftar']            = 'auth/register/index'; 
$route['auth/registration'] = 'auth/register/index';

// DASHBOARD
$route['dashboard'] = 'dashboard/dashboard/index';

