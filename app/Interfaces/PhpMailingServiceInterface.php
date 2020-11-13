<?php

	namespace App\Interfaces;

	interface PhpMailingServiceInterface {
		public function send($senderEmail = null, $senderName = null, $toEmail = null, $subject = null, $bodyHtml = "", $bodyText="", $files = []);
	}
