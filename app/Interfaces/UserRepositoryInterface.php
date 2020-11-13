<?php

	namespace App\Interfaces;


	/**
	 * Class UserRepository
	 * @package App\Repositories
	 */
	interface UserRepositoryInterface {
		/**
		 * @return mixed
		 */
		public function all();

		/**
		 * @param $userId
		 * @return mixed
		 */
		public function find($userId);
	}
