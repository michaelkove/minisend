<?php
    namespace App\Services;

    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    class UserService{


        /**
         *
         */
        public function active(){
//            this would return active user to grab via call
        }

        /**
         * @param int $paginate
         * @return mixed
         */
        public function get($paginate = 50){
            $users = new User();
            return $this->_paginate($users, $paginate);
        }

        /**
         * @param $id
         * @return mixed
         */
        public function find($id)
        {
            return User::find($id);
        }

        /**
         * @param $query
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public function searchByUserTerm($query){
            $users = User::query();
            //THIS IS NOT THE MOST EFECIENT implementation. However, for Demo purposes it's workable. For larger DATABASE should utilize a different search... like Elastic Search, Algolia, etc.
            return $users->where('name','LIKE', "%{$query}%")->orWhere('email', 'LIKE', "%$query%")->get();
        }

        /**
         * @param $userId
         * @return bool
         */
        public function authUserIsRequestingOrAdmin($userId){
            //THIS SHOULD NOT BE IMPLEMENTED HERE AND SHOULD BE HANLDED BY MIDDLEWHARE
            return (Auth::id() === $userId || $this->_isDemo() || $this->_isAdmin()); //obviously if not implemented, needs to return true
        }

        /**
         * @return bool
         */
        private function _isAdmin(){
            //IF AUTHENTICATED USER IS ADMIN - SHOULD HAVE UNRESTRICTED ACCESS
//            return Auth::user()->admin;
            return true;
        }

        /**
         * @return bool
         */
        private function _isDemo(){
            return true;
        }

        /**
         * @param $userQuery
         * @param null $paginate
         * @return mixed
         */
        private function _paginate(&$userQuery, $paginate = null){
            return ($paginate) ? $userQuery->paginate($paginate) : $userQuery->get();
        }



    }
