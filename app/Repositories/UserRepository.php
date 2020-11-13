<?php
    namespace App\Repositories;

    use App\Interfaces\UserRepositoryInterface;
    use App\Models\User;

    /**
     * Class UserRepository
     * @package App\Repositories
     */
    class UserRepository implements UserRepositoryInterface {


        /**
         * @return mixed
         */
        public function all(){
            return User::get();
        }


        /**
         * @param $userId
         * @return mixed
         */
        public function find($userId){
            return User::findOrFail($userId);
        }

    }
