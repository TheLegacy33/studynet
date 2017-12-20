<?php
/**
 * Informations concernant le serveur SMTP
 */
$smtpParams['host'] = 'mail.devatom.net';
$smtpParams['port'] = '25';
$smtpParams['helo'] = 'mail.devatom.net';
$smtpParams['auth'] = true;
$smtpParams['user'] = 'webmaster';
$smtpParams['pass'] = 'WeBm@steR';
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600);

session_name(SESSIONNAME);
session_start();
?>