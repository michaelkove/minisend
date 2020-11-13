<?php
    namespace App\Helpers;

    class GeneralHelper{

        public static function validateEmail($email){
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        /*public static function uploadFiles($files) {
            $storedAttachments = [];
//            if ($request->hasFile('attachments')) {
//                $files = $request->file('attachments');
//
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $type = $file->getClientMimeType();
                    foreach ($request->attachments as $attachment) {
                        $filename = $attachment->store('attachments');
                        if($filename){
                            $storedAttachments[] = [
                                'filename' => $filename,
                                'name' => $originalName,
                                'type' => $type,
                            ];
                        }
                    }
                }
//            }
            return $storedAttachments;
        }*/
    }
