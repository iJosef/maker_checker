<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\UserInfoPendingRequest;
use App\Repositories\UserInfoRepository;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;

class UserInfoPendingRequestController extends Controller
{

    public function __construct(UserInfoRepository $userInfoRepository)
    {
        $this->userInfoRepository = $userInfoRepository;
    }


    public function fetch_all_pending_requests()
    {

        $data = $this->userInfoRepository->all_pending_requests();

        return response()->json($data, 200);
    }


    public function create_new_user_info(StoreUserInfoRequest $storeUserInfoRequest)
    {

        $data = $this->userInfoRepository->create_user_info($storeUserInfoRequest);

        return response()->json($data, 201);

    }


    public function update_user_info(UpdateUserInfoRequest $updateUserInfoRequest)
    {

        $data = $this->userInfoRepository->update_user_information($updateUserInfoRequest);

        return response()->json($data, 201);
    }

    public function delete_user_info($id, $request_type)
    {

        $data = $this->userInfoRepository->delete_user_information($id, $request_type);

        return response()->json($data, 201);
    }


    public function approve_request($id, $request_type)
    {

        return $this->userInfoRepository->approve_a_request($id, $request_type);

    }


    public function decline_request($id)
    {
        return $this->userInfoRepository->decline_a_request($id);
    }
}
