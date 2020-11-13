<?php
    namespace App\Services;

    use App\Helpers\GeneralHelper;
    use App\Interfaces\PhpMailingServiceInterface;
    use App\Models\Recipient;

    class RecipientService{


        /**
         * @var PhpMailingServiceInterface
         */
        private $phpMailingService;

        /**
         * RecipientService constructor.
         * @param PhpMailingServiceInterface $phpMailingService
         */
        public function __construct(PhpMailingServiceInterface $phpMailingService){

            $this->phpMailingService = $phpMailingService;
        }

        /**
         * @param null $paginate
         * @param array $load
         * @return mixed
         */
        public function all($paginate = null, $load = []){
            $recipients = $this->_build_base_query([], $load);
            return $this->_paginate($recipients, $paginate);
        }

        /**
         * @param $userId
         * @param null $paginate
         * @param array $load
         * @return mixed
         */
        public function getByUserId($userId, $paginate = null, $load = []){ //should not paginate by default as this is likely to be sending
            $recipients = $this->_build_base_query([$userId], $load);
            return $this->_paginate($recipients, $paginate);
        }

        /**
         * @param $emailId
         * @param null $paginate
         * @param array $load
         * @param null $seekStatus
         * @return mixed
         */
        public function getByEmailId($emailId, $paginate = null, $load= [], $seekStatus = null){
            $recipientQuery = Recipient::query();

            $recipientQuery->whereHas('emails', function($q) use ($emailId, $seekStatus){
                $q->where('emails.id', $emailId);
                if($seekStatus){
                    $q->where('emails_recipients.status', $seekStatus);
                }
            });
            return $this->_paginate($recipientQuery, $paginate);
        }

        /**
         * @param $userId
         * @param $id
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
         */
        public function find($userId, $id, $load = [])
        {
            $recipients = $this->_build_base_query([$userId], $load);
            return $recipients->find($id);
        }

        /**
         * @param $query
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public function searchByUserTerm($query){
            $recipients = Recipient::query();
            //NOT PROD READY. DEMO ONLY
            return $recipients
                ->where('first_name','LIKE', "%{$query}%")
                ->orWhere('last_name','LIKE', "%{$query}%")
                ->orWhere('middle_name','LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->get();
        }

        /**
         * @param $userId
         * @param $emailId
         * @param $recipients
         * @return array
         */
        public function processNew($userId, $emailId, $recipients){
            $returnData = [];
            if(isset($recipients) && count($recipients) > 0){
                $attachRecipients = [];
                foreach($recipients as $rawRecipient){
                    $recipientData = [
                        'first_name' => isset($rawRecipient['first_name']) ? $rawRecipient['first_name'] : null,
                        'last_name' => isset($rawRecipient['last_name']) ? $rawRecipient['last_name'] : null,
                        'middle_name' => isset($rawRecipient['middle_name']) ? $rawRecipient['middle_name'] : null,
                        'email' => isset($rawRecipient['email']) ? $rawRecipient['email'] : null,
                        'user_id' => $userId,
                    ];
                    $recipient = $this->createUpdate($recipientData);
                    if($recipient){
                        $returnData[] = $recipient;
                    }
                }
            }
            return $returnData;
        }

        /**
         * @param array $recipientData
         * @return |null
         */
        public function createUpdate($recipientData = []){
            $email = isset($recipientData['email']) ? strtolower(trim($recipientData['email'])) : null;
            $userId = isset($recipientData['user_id']) ? $recipientData['user_id'] : null;

            //we don't want to store hanging recipients without userId attached to it.
            if($userId && $email && GeneralHelper::validateEmail($email)){
                //lets grab one if there is one
                $recipient = Recipient::firstOrNew(['user_id' => $userId, 'email' => $email]);
                $recipient->first_name = isset($recipientData['first_name']) ? $recipientData['first_name'] : $recipient->first_name;
                $recipient->last_name = isset($recipientData['last_name']) ? $recipientData['last_name'] : $recipient->last_name;
                $recipient->middle_name = isset($recipientData['middle_name']) ? $recipientData['middle_name'] : $recipient->middle_name;
                $recipient->save();
                return $recipient;
            }
            return null;

        }

        /**
         * @param $email
         * @param $user
         * @param array $attachments
         * @param string $seekStatus
         * @return bool
         */
        public function sendEmails($email, $user, $attachments = [], $seekStatus = 'posted'){
            $recipients = $this->getByEmailId($email->id, null, [], $seekStatus);
            foreach($recipients as $recipient){
                //attachment expected as an array of files
                $this->phpMailingService->send($user->email,$user->name,$recipient->email,$email->subject,$email->body_html, $email->body_text, $attachments);

//                TODO:: Demo only. Technically this should be updated by EVENT listenern.
                $this->_updateStatus($recipient, 'sent');
            }
            return true;
        }


        /**
         * @param $recipient
         * @param string $status
         */
        private function _updateStatus(&$recipient, $status = 'posted'){
            $recipient->pivot->status = $status;
            $recipient->save();
        }

        /**
         * @param $usersIds
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder
         */
        private function _build_base_query($usersIds, $load = []){
            $recipients = Recipient::query();
            if(count($usersIds)){
                $this->_users_only($recipients, $usersIds);
            }
            $this->_load_additional($recipients, $load);

            return $recipients;
        }

        /**
         * @param $recipients
         * @param array $ids
         */
        private function _users_only(&$recipients, $ids = []){
            $recipients->whereIn('user_id', $ids);
        }

        /**
         * @param $recipientQuery
         * @param null $paginate
         * @return mixed
         */
        private function _paginate(&$recipientQuery, $paginate = null){
            return ($paginate) ? $recipientQuery->paginate($paginate) : $recipientQuery->get();
        }

        /**
         * @param $recipientQuery
         * @param array $params
         */
        private function _load_additional(&$recipientQuery, $params = []){
            if(count($params)){
                $recipientQuery->with($params);
            }
        }

    }
