<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\StoreAttachmentsServiceInterface;
use App\Models\Email;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Http\Request;

class EmailController extends Controller
{


    /**
     * @var EmailRepositoryInterface
     */
    private $emailRepository;
    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var StoreAttachmentsServiceInterface
     */
    private $storeAttachmentsService;

    public function __construct(EmailService $emailService, UserService $userService, StoreAttachmentsServiceInterface $storeAttachmentsService){

        $this->emailService = $emailService;
        $this->userService = $userService;
        $this->storeAttachmentsService = $storeAttachmentsService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        if(!$this->userService->authUserIsRequestingOrAdmin($userId)) return response()->json(['error' => '401', 'messages'=> ['message' => "Unauthorized!"]], 401); //should be handled by middlewhare, DEMO only

        $results = $this->emailService->getByUserId($userId, 50, ['attachments','recipients','user']);
        return response()->json($results, 201);
    }

    public function search($userId, Request $request)
    {
        if(!$this->userService->authUserIsRequestingOrAdmin($userId)) return response()->json(['error' => '401', 'messages'=> ['message' => "Unauthorized!"]], 401); //should be handled by middlewhare, DEMO only

        $results = $this->emailService->searchUserEmails($userId, $request->all(), ['user','recipients','attachments']);
        return response()->json($results, 201);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $userId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @comment See README for consume data
     */
    public function store($userId, Request $request)
    {
        if(!$this->userService->authUserIsRequestingOrAdmin($userId)) return response()->json(['error' => '401', 'messages'=> ['message' => "Unauthorized!"]], 401); //should be handled by middlewhare, DEMO only

        //deal with attachments first so we do not carry it over into Job Dispatch and bloat the queue
        $attachments = $this->storeAttachmentsService->storeFiles($request);
        $data = $request->except('_attachments','_token','_method');
        $data['attachments'] = $attachments;
        //PASS it on for Dispatch or Instant Exec.
        $delay = false; // FOR DEMO ONLY this  should not be set here, emails should be picked up by cron/task job and ran autonomously in real life scenario
        $results = $this->emailService->storeAndSend($userId, $data, $delay, 'posted');
        return response()->json($results, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param $userId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($userId, $id)
    {
        if(!$this->userService->authUserIsRequestingOrAdmin($userId)) return response()->json(['error' => 'Unauthorized'], 401); //should be handled by middlewhare, DEMO only

        $results = $this->emailService->find($userId, $id);
        return response()->json($results, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($userId, $id, Request $request)
    {
        //NOT REQUESTED FOR IMPLEMENTATION
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $id)
    {
        //NOT REQUESTED
    }
}
