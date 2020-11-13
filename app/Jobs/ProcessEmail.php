<?php

namespace App\Jobs;

use App\Services\EmailService;
use App\Services\RecipientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var array
     */
    private $emailData;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $seekStatus;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @param array $emailData
     * @param string $seekStatus
     */
    public function __construct(int $userId, array $emailData, string $seekStatus)
    {
        //
        $this->emailData = $emailData;
        $this->userId = $userId;
        $this->seekStatus = $seekStatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //direct call, no binding
        $emailService = resolve(EmailService::class);
        $email = $emailService->createWithRecipientsAndAttachments($this->userId, $this->emailData, $this->seekStatus);
        if($email){ // LETS SEND THEM IF THERE IS EAMAIL CREATED
            $emailService->sendEmails($email, $this->seekStatus);
        }
    }
}
