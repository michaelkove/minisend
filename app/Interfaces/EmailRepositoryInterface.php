<?php

	namespace App\Interfaces;

	interface EmailRepositoryInterface {
		public function get();

		public function find(int $id = null);

		public function search(string $type, $query = null);

		public function searchBySubject(string $query = null);

		public function searchByBody(string $query = null);

		public function searchByBodyHtml(string $query = null);

		public function searchBySender(int $userId = null, string $query = null);

		public function searchByRecipient(int $userId = null, string $query = null);
	}
