<?php
    namespace App\Repositories;

    use App\Interfaces\AttachmentRepositoryInterface;
    use App\Models\Email;

    class AttachmentRepository implements AttachmentRepositoryInterface {

        /**
         * @var Email
         */
        private $email;

        public function __construct(){

        }

        public function get(){
            return Email::all();
        }

        public function find(int $id = null){
            return Email::findOrFail($id);
        }

    }
