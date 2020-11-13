<?php

	namespace App\Interfaces;

	use Illuminate\Http\Request;

	interface StoreAttachmentsServiceInterface {
		public function storeFiles(Request $request, $varName = 'attachments');
	}
