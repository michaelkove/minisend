<?php

namespace App\Mail;

use App\Models\Email;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UserEmailSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Email
     */
    public $email;
    /**
     * @var null
     */
    public $senderEmail;
    /**
     * @var null
     */
    public $senderName;
    /**
     * @var null
     */
    public $toEmail;
    /**
     * @var string
     */
    public $bodyHtml;
    /**
     * @var string
     */
    public $bodyText;
    /**
     * @var array
     */
    public $fattachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($senderEmail = null, $senderName = null, $toEmail = null, $subject = null, $bodyHtml = "", $bodyText="", $attachments = [])
    {
        //

        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
        $this->toEmail = $toEmail;
        $this->sub = $subject;
        $this->bodyHtml = $bodyHtml;
        $this->bodyText = $bodyText;
        $this->fattachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from($this->senderEmail,$this->senderName)
            ->view('email.html')
            ->text('email.text')
            ->with(['bodyHtml' => $this->bodyHtml, 'bodyText'=>$this->bodyText])
            ->subject($this->sub);
        // $attachments is an array with file paths of attachments
        if($this->fattachments){
            foreach($this->fattachments as $attachment){
                try{
                    $email->attach($attachment['filename'], [
                        'as' => $attachment['name'],
                        'mime' => $attachment['type'],
                    ]);
                } catch (\Exception $e){

                }

            }
        }

        return $email;
    }
}
