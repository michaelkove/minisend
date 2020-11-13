<?php
    namespace App\Services;

    use App\Helpers\GeneralHelper;
    use App\Jobs\ProcessEmail;
    use App\Models\Email;

    class EmailService{

        /**
         * @var RecipientService
         */
        private $recipientService;

        /**
         * EmailService constructor.
         * @param RecipientService $recipientService
         */
        public function __construct(RecipientService $recipientService){

            $this->recipientService = $recipientService;
            $this->_messages = [];
        }

        /**
         * @param null $paginate
         * @param array $load
         * @return array
         */
        public function all($paginate = null, $load = []){
            $emails = $this->_build_base_query([], $load);
            return $this->_paginate($emails, $paginate);
        }

        /**
         * @param $userId
         * @param int $paginate
         * @param array $load
         * @return array
         */
        public function getByUserId($userId, $paginate = 50, $load = []){
            $emails = $this->_build_base_query([$userId], $load);
            return $this->_paginate($emails, $paginate);
        }

        /**
         * @param $userId
         * @param $id
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
         */
        public function find($userId, $id, $load = [])
        {
            $emails = $this->_build_base_query([$userId], $load);
            return $emails->find($id);
        }

        /**
         * @param $userId
         * @param string[] $terms
         * @param array $load
         * @param int $paginate
         * @return array
         */
        public function searchUserEmails($userId, $terms = ['location' => 'all', 'q' => ""],  $load=[], $paginate = 50){
            $emails = $this->_build_base_query([$userId], $load);
            $searchLocation = $terms['location'];
            $query = trim($terms['q']);
            if($query && $query !== ""){
                switch($searchLocation){
                    case 'all':
                        $this->_built_all_search($emails, $query);
                        break;
                    case 'sender':
                        $this->_built_sender_search($emails, $query);
                        break;
                    case 'subject':
                        $this->_built_subject_search($emails, $query);
                        break;
                    case 'recipient':
                        $this->_built_recipient_search($emails, $query);
                        break;
                    default:
                        // DO NOTHING
                        break;
                }
            }
            if($emails->count() === 0){
                $this->_messages[] = ['message' => "Nothing was found for term ".$query, 'warning'];
            }
            return $this->_paginate($emails, $paginate);
        }

        /**
         * @param $userId
         * @param $rawData
         * @param bool $delay
         * @param string $seekStatus
         * @return array[]
         */
        public function storeAndSend($userId, $rawData, $delay = true, $seekStatus = 'posted'){

            if($delay){
                ProcessEmail::dispatch($userId, $rawData, $seekStatus);
                $this->_messages[] = ['message' => "Email was scheduled", 'type' => 'info'];
            } else {
                $email = $this->createWithRecipientsAndAttachments($userId, $rawData, $seekStatus);
                $sentResults = $this->sendEmails($email, $seekStatus);
                if(!$email){
                    $this->_messages[] = ['message' => "Error: Email was NOT created.", 'type' => 'danger'];
                } else {
                    $this->_messages[] = ['message' => "Email was posted.", 'type' => 'success'];
                }
                if(!$sentResults){
                    $this->_messages[] = ['message' => "Error: Email was NOT sent.", 'type' => 'danger'];
                } else {
                    $this->_messages[] = ['message' => "Email was sent.", 'type' => 'success'];
                }
            }
            return ['messages' => $this->_messages];
        }

        /**
         * @param $userId
         * @param $emailData
         * @return mixed
         */
        public function create($userId, $emailData){
            $storeEmailData = [
                'subject' => isset($emailData['subject']) ? $emailData['subject'] : null,
                'body_text' => isset($emailData['body_text']) ? $emailData['body_text'] : null,
                'body_html' => isset($emailData['body_html']) ? $emailData['body_html'] : null,
                'user_id' => $userId
            ];
            return Email::create($storeEmailData);

        }

        /**
         * @param $userId
         * @param $emailData
         * @param string $status
         * @return mixed
         */
        public function createWithRecipientsAndAttachments($userId, $emailData, $status = 'posted'){

            $email = $this->create($userId, $emailData);
            if($emailData['recipients']){
                $recipientsToAttach = $this->recipientService->processNew($userId,$email->id, $emailData['recipients']);
                $this->_syncRecipients($email, $recipientsToAttach, $status);
            }
            if($emailData['attachments']){
                $attachmentService = new AttachmentService();
                $attachmentService->processNew($email->id, $emailData['attachments']);
            }
            return $email;
        }

        /**
         * @param null $userId
         * @param null $paginate
         * @param string[] $load
         * @param string $seekStatus
         * @return array
         */
        public function getUnsent($userId = null, $paginate = null, $load = ['recipients','user','attachment'], $seekStatus = 'posted'){
            $userIds = ($userId) ? [$userId] : [];
            $emails = $this->_build_base_query($userIds, $load);
            $emails->whereHas('recipients', function($q) use ($seekStatus){
                $q->where('emails_recipients.status',$seekStatus);
            });
            return $this->_paginate($emails, $paginate);
        }

        /**
         * @param $email
         * @param $recipient
         * @param array $pivotValues
         */
        public function syncRecipient(&$email, &$recipient, $pivotValues = []){
            //Not really used anywhere...
            $email->recipients()->syncWithoutDetaching($recipient->id, $pivotValues);
        }

        /**
         * @param $email
         * @param string $seekStatus
         * @return bool
         */
        public function sendEmails($email, $seekStatus = 'posted'){
            if($email){
                $userService = new UserService();
                $attachmentService = new AttachmentService();
                $user  = $userService->find($email->user_id);
                $attachments = $attachmentService->prepForSending($email->id);
                if($user){
                    //attachment is consumed as array in Rec.serv.
                    $this->recipientService->sendEmails($email, $user, $attachments, $seekStatus);
                }
                return true;
            }
            return false;
        }

        /**
         * @param $email
         * @param $recipients
         * @param string $status
         * @comment Mass sync, to avoid hitting DB for each recepient
         */
        private function _syncRecipients(&$email, $recipients, $status = 'posted'){
            //prep data for attachment
            $syncData = [];
            foreach($recipients as $recipient){
                $syncData[$recipient->id] = ['status' => $status];
            }
            $email->recipients()->syncWithoutDetaching($syncData);
        }

        /**
         * @param $usersIds
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder
         */
        private function _build_base_query($usersIds, $load = []){
            $emails = Email::query();
            if(count($usersIds)){
                $this->_users_only($emails, $usersIds);
            }
            $this->_load_additional($emails, $load);

            return $emails;
        }

        /**
         * @param $emails
         * @param string $query
         */
        private function _built_sender_search(&$emails, $query = ""){
            $userService = resolve(UserService::class);
            $sendersIds = $userService->searchByUserTerm($query)->pluck('id');
            $emails->whereHas('user',function($q) use ($sendersIds){
                $q->whereIn('users.id', $sendersIds);
            });
        }

        /**
         * @param $emails
         * @param string $query
         */
        private function _built_recipient_search(&$emails, $query = ""){
            $recipientService = resolve(RecipientService::class);
            $recipientIds = $recipientService->searchByUserTerm($query)->pluck('id');
            $emails->whereHas('recipients',function($q) use ($recipientIds){
                $q->whereIn('recipients.id', $recipientIds);
            });
        }

        /**
         * @param $emails
         * @param $query
         */
        private function _built_subject_search(&$emails, $query){
            $emails->where('emails.subject','LIKE', "%$query%");
        }

        /**
         * @param $emails
         * @param $query
         */
        private function _built_all_search(&$emails, $query){
            $userService = resolve(UserService::class);
            $recipientService = resolve(RecipientService::class);
            $sendersIds = $userService->searchByUserTerm($query)->pluck('id');
            $recipientIds = $recipientService->searchByUserTerm($query)->pluck('id');
            $emails->whereHas('user',function($q) use ($sendersIds){
                $q->whereIn('users.id', $sendersIds);
            })->orWhereHas('recipients',function($q) use ($recipientIds){
                $q->whereIn('recipients.id', $recipientIds);
            })->orWhere('emails.subject','LIKE', "%$query%");
        }

        /**
         * @param $emails
         * @param array $ids
         */
        private function _users_only(&$emails, $ids = []){
            $emails->whereIn('emails.user_id', $ids);
        }

        /**
         * @param $emailQuery
         * @param null $paginate
         * @return array
         */
        private function _paginate(&$emailQuery, $paginate = null){
            return [
                'emails' => ($paginate) ? $emailQuery->paginate($paginate) : $emailQuery->get(),
                'messages' => $this->_messages
            ];
        }

        /**
         * @param $emailQuery
         * @param array $params
         */
        private function _load_additional(&$emailQuery, $params = []){
            if(is_array($params) && count($params)){
                $emailQuery->with($params);
            }
        }


    }
