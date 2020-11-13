<?php

	namespace App\Interfaces;

	interface RecipientRepositoryInterface {
		public function get();

		public function find(int $id = null);

		public function search(string $query = null);

		public function emails();
	}
