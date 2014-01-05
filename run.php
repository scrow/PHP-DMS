<?php
/**
 * run.php:
 * 
 * This is the main application script.  It can be run either from Apache or the
 * PHP CLI.  Either way, a cron job should call this script exactly once daily.
 * 
 * No parameters are required.
 **/

require_once('globals.php');
require_once('functions.php');

// Fetch the current day number.
$dayNum = getDay();

// First task:  generate the check-in request e-mail if needed

// Determine if today falls on a checkInterval
if(($dayNum % globals::checkInterval) == 0) {
	// Falls on a checkInterval

	// Determine if we have reached sendAfter
	if($dayNum < globals::sendAfter) {
		// Have not reached sendAfter

		// Build the check-in request message
		$msgText='It\'s time to check in with DMS.'."\r\n\r\n";
	        $msgText=$msgText.'Currently on day number '.getDay().' since last check-in.'."\r\n\r\n";
	        $msgText=$msgText.'Messages are configured to release after '.globals::sendAfter.' days.'."\r\n\r\n";

		// Add warning text if this is the last check-in request before
		// reaching the sendAfter date
		if((globals::sendAfter - $dayNum) <= globals::checkInterval) {
			$msgText=$msgText.' ** WARNING:  THIS IS YOUR FINAL NOTIFICATION! ** '."\r\n\r\n";
		};

		// Generate the check-in link with the data from token.dat
	        $msgText=$msgText.globals::webPath.'/checkin.php?token='.getToken();

		// Send the check-in message
		mail(globals::ownerMail,'DMS Check-In Required',$msgText,'From: '.globals::mailFrom);
	};

	// Officially advance the daynum.dat file
	writeDay($dayNum);
} else {
	// Does not fall on a checkInterval

	// Officially advance the daynum.dat file
	writeDay($dayNum);
};

// Second task:  send messages if required

// Determine if we have reached sendAfter
if($dayNum >= globals::sendAfter) {
	// We have reached sendAfter

	// Determine how far past sendAfter
	$daysAfter = ($dayNum - globals::sendAfter);

	// Initial scan for recipient e-mail addresses
	$targetAddrList = array_filter(glob(globals::baseFolder.'/data/*'), 'is_dir');

	// Iterate through the list of e-mail addresses
	foreach($targetAddrList as $targetAddr) {
		// Within the folder for each recipient, step through the
		// numbered folders.  We could go to $targetAddr.'/'.$daysAfter, but
 		// let's evaluate each folder in case of leading zeroes or other
		// naming issues.

		// Scan for numbered folders
		$numberedFolders = array_filter(glob($targetAddr.'/*'), 'is_dir');

		// Iterate through the list of numbered folders
		foreach($numberedFolders as $numberedFolder) {
			// See if the numbered folder name matches daysAfter
			if(intval(basename($numberedFolder),10) == $daysAfter) {
				// Matches daysAfter

				// Scan for message files
				$messageDir=$numberedFolder;
				$messageFiles=scandir($messageDir);

				// Iterate through the list of message files
				foreach($messageFiles as $messageFile) {
					// Sanity check - is it a file?
					if(is_file($messageDir.'/'.$messageFile)) {
						// Read the message and send it
						sendMsg(basename($targetAddr),$messageDir.'/'.$messageFile);
					}; // end sanity check
				}; // end message file iteration
			}; // end numbered folder name evaluation
		}; // end numbered folder iteration
	}; // end e-mail address iteration
}; // end sendAfter evaluation

?>
