<?php
/**
 * checkin.php:
 * 
 * This script is triggered manually by the PHP-DMS Owner when clicking the
 * embedded check-in link within the periodic check-in request e-mails.
 * It is intended to be called from an Apache web server, but PHP CLI
 * access is also supported by passing 'token=x' as a parameter.
 * 
 * It is probably a good idea to password-protect this one file by way of
 * .htaccess and .htpasswd.  A sample .htaccess file is provided with the
 * distribution bundle.
 **/

require_once('globals.php');
require_once('functions.php');

// In case we want to call this from the CLI
if (PHP_SAPI === 'cli') {
	parse_str(implode('&', array_slice($argv, 1)), $_GET);
};

// See if the required token is provided.
if(!isset($_GET['token'])) {
	die('<P>Missing token.</P>');
};

// Compare the token provided to the token.dat file.
if(intval($_GET['token'],10)!==getToken()) {
	die('<P>Invalid token.</P>');
} else {
	// Kill off this token and generate a new one.
	randomizeToken();
	// Reset the day number back to 0.
	resetDayNum();
	echo('<P>Checked in.</P>');
};

?>
