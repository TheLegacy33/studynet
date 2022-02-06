<?php
session_name(SESSIONNAME);
session_start();

if( !isset($_SESSION['LASTACTIONTIME']) || (time() - $_SESSION['LASTACTIONTIME']) > 60 )
	$_SESSION['LASTACTIONTIME'] = time();

/**
 * Informations concernant le serveur SMTP
 */
$smtpParams['host'] = 'mail.devatom.net';
$smtpParams['port'] = '25';
$smtpParams['helo'] = 'mail.devatom.net';
$smtpParams['auth'] = true;
$smtpParams['user'] = 'webmaster';
$smtpParams['pass'] = 'WeBm@steR';

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
		case 'T':
			$iValue *= 1024;
		case 'G':
			$iValue *= 1024;
		case 'M':
			$iValue *= 1024;
		case 'K':
			$iValue *= 1024;
			break;
	}
	return (int)$iValue;
}

function randomPassword($length = 8) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	return substr(str_shuffle($chars), 0, $length);
}

function sessionStatus(){
	$ret = session_status();
	switch ($ret){
		case PHP_SESSION_NONE:{
			return 'Sessions actives mais aucune session existante';
		}
		case PHP_SESSION_ACTIVE:{
			return 'Sessions actives avec une session existante';
		}
		case PHP_SESSION_DISABLED:{
			return 'Sessions désactivées';
		}
		default:{
			return 'Erreur de session';
		}
	}
}

function Debug($message = "", $variable = null){
    global $debugbar;
    if (!is_null($message)){
        $debugbar["messages"]->addMessage($message);
    }
    if (!is_null($variable)){
        $debugbar["messages"]->addMessage("Variable : ".$variable);
    }
}

function date_fr_to_mysql($datefr){
	if (!is_null($datefr)){
		return date_create_from_format('d/m/Y', $datefr)->format('Y-m-d');
	}else{
		return '';
	}
}

if ( ! function_exists( 'array_key_last' ) ) {
	/**
	 * Polyfill for array_key_last() function added in PHP 7.3.
	 *
	 * Get the last key of the given array without affecting
	 * the internal array pointer.
	 *
	 * @param array $array An array
	 *
	 * @return mixed The last key of array if the array is not empty; NULL otherwise.
	 */
	function array_key_last( $array ) {
		$key = NULL;

		if ( is_array( $array ) ) {
			end( $array );
			$key = key( $array );
		}

		return $key;
	}
}
?>