<?php

    namespace App\Http\Controllers;

    use App\Services\UserService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class DashboardController extends Controller
    {


        /**
         * @var UserService
         */
        private $userService;

        /**
         * DashboardController constructor.
         * @param UserService $userService
         */
        public function __construct(UserService $userService){

            $this->userService = $userService;
        }

        /**
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function index(){
            return view('home',['users' => $this->userService->get(null)]); //for testing purposes no need to paginate users...obviously this form would never exist on prod
        }

        /**
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function doc(){
            return view('documentation'); //for testing purposes no need to paginate users...obviously this form would never exist on prod
        }

        /**
         * @param Request $request
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
         */
        public function simulatedLogoff(Request $request){
            //bye bye
            \Auth::logout();
            return redirect('/');
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function simulatedLogin(Request $request){
            //this is not real login, just for example sake
            //I need to get Auth::user for API
            $userId = $request->user_id;
            $user = Auth::loginUsingId($userId,true);
            //let's auth those guy into both api AND web
            if($user){
                auth('api')->setUser($user);
                return redirect()->route('dashboard');
            }
        }

        /**
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function dashboard(){
            return view('dashboard-app', ['user' => auth()->user()]);
        }
    }
