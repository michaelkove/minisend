<?php
    namespace App\Services;

    use App\Helpers\GeneralHelper;
    use App\Models\Attachment;

    class AttachmentService{


        /**
         * @param array $load
         * @param int $paginate
         * @return mixed
         */
        public function all($load = [], $paginate = 50){ //only used when assembling ALL attachments from all emails.
            $attachments = $this->_build_base_query([], $load);
            return $this->_paginate($attachments, $paginate);
        }

        /**
         * @param $emailId
         * @param array $load
         * @return mixed
         */
        public function getByEmailId($emailId, $load = []){ //no need to paginate handful of attachments for email
            $attachments = $this->_build_base_query([$emailId], $load);
            return $this->_paginate($attachments);
        }

        /**
         * @param $emailId
         * @param $id
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
         */
        public function find($emailId, $id, $load = [])
        {
            $attachments = $this->_build_base_query([$emailId], $load);
            return $attachments->find($id);
        }

        /**
         * @param $emailId
         * @param array $attachments
         * @return array
         */
        public function processNew($emailId, $attachments = []){
            $returnData = [];
            if($attachments && count($attachments) > 0){
                foreach($attachments as $rawAttachment){
                    $randomName = md5(time());
                    $attachmentData = [
                        'name' => isset($rawAttachment['name']) ? $rawAttachment['name'] : $randomName,
                        'filename' => isset($rawAttachment['filename']) ? $rawAttachment['filename'] : $randomName,
                        'type' => isset($rawAttachment['type']) ? $rawAttachment['type'] : 'unknown',
                        'email_id' => $emailId,
                    ];
                    $attachment = $this->createUpdate($attachmentData);
                    if($attachment){
                        $returnData[] = $attachment;
                    }
                }
            }
            return $returnData;
        }

        /**
         * @param array $attachmentData
         * @return |null
         */
        public function createUpdate($attachmentData = []){
            $emailId = isset($attachmentData['email_id']) ? $attachmentData['email_id'] : null;
            $name = isset($attachmentData['name']) ? $attachmentData['name'] : null;
//            //we don't want to store hanging attachments without emailId attached to it.
            if($emailId) {
//                //lets grab one if there is one
                $attachment = Attachment::firstOrNew(['email_id' => $emailId, 'name' => $name]);
                $attachment->filename = isset($attachmentData['filename']) ? $attachmentData['filename'] : $attachment->filename;
                $attachment->type = isset($attachmentData['type']) ? $attachmentData['type'] : $attachment->type;
                $attachment->save();
                return $attachment;
            }
            return null;
        }

        /**
         * @param $emailId
         * @return array
         */
        public function prepForSending($emailId){
            $attachmentData = [];
            $attachments = $this->getByEmailId($emailId);
            foreach($attachments as $attachment){
                $attachmentData[] = [
                    'name' => $attachment->name,
                    'filename' => $attachment->filename,
                    'type' => $attachment->type,
                ];
            }
            return $attachmentData;
        }

        /**
         * @param $emailId
         * @param array $load
         * @return \Illuminate\Database\Eloquent\Builder
         */
        private function _build_base_query($emailId, $load = []){
            $attachments = Attachment::query();
            if(count($emailId)){
                $this->_emails_only($attachments, $emailId);
            }
            $this->_load_additional($attachments, $load);

            return $attachments;
        }

        /**
         * @param $attachments
         * @param array $ids
         */
        private function _emails_only(&$attachments, $ids = []){
            $attachments->whereIn('email_id', $ids);
        }

        /**
         * @param $attachmentQuery
         * @param null $paginate
         * @return mixed
         */
        private function _paginate(&$attachmentQuery, $paginate = null){
            return ($paginate) ? $attachmentQuery->paginate($paginate) : $attachmentQuery->get();
        }

        /**
         * @param $attachmentQuery
         * @param array $params
         */
        private function _load_additional(&$attachmentQuery, $params = []){
            if(count($params)){
                $attachmentQuery->with($params);
            }
        }

    }
