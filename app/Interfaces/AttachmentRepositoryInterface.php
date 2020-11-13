<?php

	namespace App\Interfaces;

	interface AttachmentRepositoryInterface {
		public function get();

		public function find(int $id = null);
	}
