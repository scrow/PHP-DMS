<?php

class globals {
	/**
	 * Contains global variables for reference throughout the system.
	 **/

	const baseFolder    = '/var/www/php-dms';
	const dataFile      = '/var/www/php-dms/daynum.dat';
	const footerFile    = '/var/www/php-dms/footer.txt';
	const tokenFile     = '/var/www/php-dms/token.dat';
	const checkInterval = 3;
	const sendAfter     = 30;
	const webPath       = 'http://hostname.domainname/php-dms';
	const ownerMail     = 'user@domainname';
	const mailFrom      = 'user@domainname';
	const subjectPrefix = 'DMS-Notify: ';
}

?>
