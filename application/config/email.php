<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com';
$config['smtp_port']   = 587;
$config['smtp_user']   = '';     // ganti
$config['smtp_pass']   = '';    // gunakan App Password
$config['smtp_crypto'] = 'tls';
$config['mailtype']    = 'text'; 
$config['charset']     = 'utf-8';
$config['wordwrap']    = TRUE;
$config['newline']     = "\r\n";
