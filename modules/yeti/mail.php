<?php
	
	function mail_send($to, $body, $subject, $fromaddress, $fromname, $attachments=false) {
		$eol="\r\n";
		$mime_boundary=md5(time());

		# Common Headers
		$headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
		$headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
		$headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol;    // these two to set reply address
		$headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;
		$headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters

		# SEND THE EMAIL
		ini_set(sendmail_from, $fromaddress);  // the INI lines are to force the From Address to be used !
		$mail_sent = mail($to, $subject, $body, $headers);
		ini_restore(sendmail_from);
		
		$mail_array = array('to' => $to, 'subject' => $subject, 'body' => $body, 'headers' => $headers, 'backtrace' => false);
		
		return $mail_sent;
	}

?>
