<?php
    namespace App\Services;


    use App\Interfaces\StoreAttachmentsServiceInterface;
    use Illuminate\Http\Request;

    class StoreAttachmentsService implements StoreAttachmentsServiceInterface {

        /**
         * @param Request $request
         * @param string $varName
         * @return array
         * @comment storing files right away so they don't linger in Dispatch Data
         */
        public function storeFiles(Request $request, $varName = 'attachments'){
            $storedFiles = [];
            if($request->hasFile($varName))
            {
                $storedFiles = $this->_uploadFiles($request->file($varName));
            }
            return $storedFiles;
        }

        /**
         * @param $files
         * @return array
         */
        private function _uploadFiles($files) {
            $storedAttachments = [];
//            if ($request->hasFile('attachments')) {
//                $files = $request->file('attachments');
//
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $type = $file->getClientMimeType();
                $filename = $file->store('attachments');
                if($filename){
                    $storedAttachments[] = [
                        'filename' => $filename,
                        'name' => $originalName,
                        'type' => $type,
                    ];
                }
            }
            return $storedAttachments;
        }

    }


