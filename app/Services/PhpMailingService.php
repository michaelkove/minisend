<?php
    namespace App\Services;

    use App\Interfaces\PhpMailingServiceInterface;
    use App\Mail\UserEmailSend;

    class PhpMailingService implements PhpMailingServiceInterface {


        /**
         * PhpMailingService constructor.
         * @comment this Interfaced Service can be swapped for whatever email implementation is used
         */
        public function __construct(){

        }

        /**
         * @param null $senderEmail
         * @param null $senderName
         * @param null $toEmail
         * @param null $subject
         * @param string $bodyHtml
         * @param string $bodyText
         * @param array $files
         * @return bool
         */
        public function send($senderEmail = null, $senderName = null, $toEmail = null, $subject = null, $bodyHtml = "", $bodyText="", $files = []){


            if($toEmail) {
                return true;
                //Had an issue sending email from DOCKER temporarily disabled for demo
                $userEmailSend = new UserEmailSend($senderEmail, $senderName, $toEmail, $subject, $bodyHtml, $bodyText, $files);
                return \Mail::to($toEmail)->send($userEmailSend);
            }
            return false;
        }
    }
