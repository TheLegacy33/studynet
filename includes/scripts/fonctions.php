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


session_name(SESSIONNAME);
session_start();
if( !isset($_SESSION['LASTACTIONTIME']) || (time() - $_SESSION['LASTACTIONTIME']) > 60 )
	$_SESSION['LASTACTIONTIME'] = time();

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

function randomPassword( $length = 8 ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	$password = substr( str_shuffle( $chars ), 0, $length );
	return $password;
}
?>