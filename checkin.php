<?php

require_once('globals.php');
require_once('functions.php');

// In case we want to call this from the CLI
if (PHP_SAPI === 'cli') {
	parse_str(implode('&', array_slice($argv, 1)), $_GET);
};

if(!isset($_GET['token'])) {
	die('<P>Missing token.</P>');
};

if(intval($_GET['token'],10)!==getToken()) {
	die('<P>Invalid token.</P>');
} else {
	randomizeToken();
	resetDayNum();
	echo('<P>Checked in.</P>');
};

?>
