<?php
require_once('globals.php');
require_once('functions.php');

$dayNum = getDay();

if(($dayNum % globals::checkInterval) == 0) {
	if($dayNum < globals::sendAfter) {
		$msgText='It\'s time to check in with DMS.'."\r\n\r\n";
	        $msgText=$msgText.'Currently on day number '.getDay().' since last check-in.'."\r\n\r\n";
	        $msgText=$msgText.'Messages are configured to release after '.globals::sendAfter.' days.'."\r\n\r\n";
		if((globals::sendAfter - $dayNum) <= globals::checkInterval) {
			$msgText=$msgText.' ** WARNING:  THIS IS YOUR FINAL NOTIFICATION! ** '."\r\n\r\n";
		};
	        $msgText=$msgText.globals::webPath.'/checkin.php?token='.getToken();
		mail(globals::ownerMail,'DMS Check-In Required',$msgText,'From: '.globals::mailFrom);
	};
	writeDay($dayNum);
} else {
	writeDay($dayNum);
};

if($dayNum >= globals::sendAfter) {
	$daysAfter = ($dayNum - globals::sendAfter);
	$targetAddrList = array_filter(glob(globals::baseFolder.'/data/*'), 'is_dir');
	foreach($targetAddrList as $targetAddr) {
		$numberedFolders = array_filter(glob($targetAddr.'/*'), 'is_dir');
		foreach($numberedFolders as $numberedFolder) {
			if(intval(basename($numberedFolder),10) == $daysAfter) {
				$messageDir=$numberedFolder;
				$messageFiles=scandir($messageDir);
				foreach($messageFiles as $messageFile) {
					if(is_file($messageDir.'/'.$messageFile)) {
						// Read the message file and send it ****
						sendMsg(basename($targetAddr),$messageDir.'/'.$messageFile);
					};
				};
			};
		};
	};
};

?>
