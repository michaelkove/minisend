<?php
    namespace App\Repositories;

    use App\Interfaces\EmailRepositoryInterface;
    use App\Models\Email;
    use App\Models\User;

    class EmailRepository implements EmailRepositoryInterface {

        /**
         * @var User
         */
        private $user;
        /**
         * @var RecipientRepositoryInterface
         */
        private $recipientRepository;
        /**
         * @var UserRepositoryInterface
         */
        private $userRepository;

        public function __construct(User $user){
            $this->user = $user;
        }

        public function get(){
            return $this->user->emails;
        }

        public function find(int $id = null){
            return $this->user->emails()->findOrFail($id);
        }

        public function search(string $type, $query = null){
            $query = strtolower(trim($query));
            switch($type){
                case 'subject':
                    return $this->searchBySubject($query);
                    break;
                case 'body':
                    return $this->searchByBody($query);
                    break;
                case 'sender':
                    return $this->searchBySender($query);
                    break;
                case 'recipient':
                    return $this->searchByRecipient($query);
                    break;
            }
        }

        public function searchBySubject(string $query = null){
            return $this->user->emails()
                ->where('subject', 'like', $query.'%')
                ->get();
        }

        public function searchByBody(string $query = null){
            return $this->user->emails()
                ->where('body_text', 'like', $query.'%')
                ->orWhere('body_html', 'like', $query.'%')
                ->get();
        }

        public function searchByBodyHtml(string $query = null){
            return $this->searchByBody($query);
        }

        public function searchBySender(int $userId = null, string $query = null){
            $users = $this->userRepository->search($query);
            return $this->user->emails()
                ->whereHas('users', function($query) use ($users){
                    $query->whereIn('users.id', $users);
                });
        }

        public function searchByRecipient(int $userId = null, string $query = null){
            $recipients = $this->recipientRepository->search($query)->pluck('id');
            return $this->user->emails()
                ->whereHas('recipients', function($query) use ($recipients){
                    $query->whereIn('recepients.id', $recipients);
                });

        }


    }
