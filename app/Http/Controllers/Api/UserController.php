<?php
    namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService){


        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->userService->get(), 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // NOT REQUESTED BY REQUIREMENTS, DEMO ONLY
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->userService->authUserIsRequestingOrAdmin($id)) return response()->json(['error' => 'Unauthorized'], 401); //should be handled by middlewhare, DEMO only
        return response()->json($this->userService->find($id), 201);
    }

    public function active(){
        return response()->json($this->userService->active(), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // NOT REQUESTED BY REQUIREMENTS, DEMO ONLY
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // NOT REQUESTED BY REQUIREMENTS, DEMO ONLY
    }

}
