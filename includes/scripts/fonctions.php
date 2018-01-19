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

function getMaximumFileUploadSize(){
	return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));
}

/**
 * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
 *
 * @param string $sSize
 * @return integer The value in bytes
 */
function convertPHPSizeToBytes($sSize){
	//
	$sSuffix = strtoupper(substr($sSize, -1));
	if (!in_array($sSuffix,array('P','T','G','M','K'))){
		return (int)$sSize;
	}
	$iValue = substr($sSize, 0, -1);
	switch ($sSuffix) {
		case 'P':
			$iValue *= 1024;
		// Fallthrough intended
		case 'T':
			$iValue *= 1024;
		// Fallthrough intended
		case 'G':
			$iValue *= 1024;
		// Fallthrough intended
		case 'M':
			$iValue *= 1024;
		// Fallthrough intended
		case 'K':
			$iValue *= 1024;
			break;
	}
	return (int)$iValue;
}
?>