<?php
    namespace App\Repositories;


    use App\Interfaces\RecipientRepositoryInterface;
    use App\Models\User;

    class RecipientRepository implements RecipientRepositoryInterface {

        /**
         * @var User
         */
        private $user;

        public function __construct(User $user){

            $this->user = $user;
        }

        public function get(){

        }

        public function find(int $id = null){

        }

        public function search(string $query = null){

        }

        public function emails(){

        }

    }
