<?php


namespace App\Repositories;

use Mail;
use App\Models\User;
use App\Models\UserInfo;
use App\Mail\ApproveRequest;
use App\Events\RequestApprovalEvent;
use App\Models\UserInfoPendingRequest;


class UserInfoRepository
{
    public function all_pending_requests()
    {
        return UserInfoPendingRequest::where('created_by', '!=', auth()->user()->id)->where('approved_flag', false)->with(['request_type', 'admin_creator'])->get();
    }


    public function create_user_info($validated_fields)
    {
        $new_user_info = UserInfoPendingRequest::create([
            'first_name'        =>  $validated_fields['first_name'],
            'last_name'         =>  $validated_fields['last_name'],
            'email'             =>  $validated_fields['email'],
            'created_by'        =>  auth()->user()->id,
            'request_type_id'   =>  $validated_fields['request_type'],
        ]);

        $admin_users = User::select('email')->get();

        foreach ($admin_users as $admin_user) {
            event(new RequestApprovalEvent($admin_user));
        }

        return $data = [
            'status' => 'success',
            'message' => 'New user information created successfully and has been sent for approval'
        ];
    }


    public function update_user_information($validated_fields)
    {
        $new_pending_request = UserInfoPendingRequest::create([
            'first_name'        =>  $validated_fields['first_name'],
            'last_name'         =>  $validated_fields['last_name'],
            'email'             =>  $validated_fields['email'],
            'user_info_id'      =>  $validated_fields['user_info_id'],
            'created_by'        =>  auth()->user()->id,
            'request_type_id'   =>  $validated_fields['request_type'],
        ]);

        $admin_users = User::select('email')->get();

        foreach ($admin_users as $admin_user) {
            event(new RequestApprovalEvent($admin_user));
        }

        return $data = [
            'status' => 'success',
            'message' => 'New user information update will be implemented once approved'
        ];
    }


    public function delete_user_information($id, $request_type)
    {
        $user_info = UserInfo::where('id', $id)->first();

        $new_pending_request = UserInfoPendingRequest::create([
            'first_name'        =>  $user_info->first_name,
            'last_name'         =>  $user_info->last_name,
            'email'             =>  $user_info->email,
            'user_info_id'      =>  $id,
            'created_by'        =>  auth()->user()->id,
            'request_type_id'   =>  $request_type,
        ]);

        $admin_users = User::select('email')->get();

        foreach ($admin_users as $admin_user) {
            event(new RequestApprovalEvent($admin_user));
        }

        $data = [
            'status' => 'success',
            'message' => 'User information will be deleted once approved'
        ];
    }


    public function approve_a_request($id, $request_type)
    {
        $pending_request = UserInfoPendingRequest::where('id', $id)->first();

        if ($pending_request->created_by != auth()->user()->id) {
            $pending_request->approved_flag = true;
            $pending_request->approved_by = auth()->user()->id;
            $pending_request->update();

            if ($request_type == 1) {

                return $this->approve_create_request($pending_request);

            } else if($request_type == 2) {

                return $this->approve_update_request($pending_request);


            } else if ($request_type == 3) {

                return $this->approve_delete_request($pending_request);

            }

        } else {
            $data = [
                'status' => 'error',
                'message' => 'Cannot approve request created by you.'
            ];

            return response()->json($data, 401);
        }
    }


    public function approve_create_request($pending_request)
    {
        $new_user_info = UserInfo::create([
            'first_name'        =>  $pending_request->first_name,
            'last_name'         =>  $pending_request->last_name,
            'email'             =>  $pending_request->email,
            'created_by'        =>  $pending_request->created_by,
            'approved_by'       =>  $pending_request->approved_by
        ]);

        $pending_request->delete();

        $data = [
            'status' => 'success',
            'message' => 'New User Information created successfully'
        ];

        return response()->json($data, 201);
    }


    public function approve_update_request($pending_request)
    {
        $user_info = UserInfo::where('id', $pending_request->user_info_id)->first();
        $user_info->first_name          = $pending_request->first_name;
        $user_info->last_name           = $pending_request->last_name;
        $user_info->email               = $pending_request->email;
        $user_info->created_by          = $pending_request->created_by;
        $user_info->approved_by         = $pending_request->approved_by;
        $user_info->update();


        $pending_request->delete();

        $data = [
            'status' => 'success',
            'message' => 'User Information updated successfully'
        ];

        return response()->json($data, 200);
    }


    public function approve_delete_request($pending_request)
    {
        $user_info = UserInfo::where('id', $pending_request->user_info_id)->delete();

        $data = [
            'status' => 'success',
            'message' => 'User Information deleted successfully'
        ];

        return response()->json($data, 200);
    }


    public function decline_a_request($id)
    {
        $pending_request = UserInfoPendingRequest::where('id', $id)->first();
        if ($pending_request->created_by != auth()->user()->id) {
            $pending_request->delete();

            $data = [
                'status' => 'success',
                'message' => 'Request declined successfully'
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Cannot decline request created by you.'
            ];

            return response()->json($data, 401);
        }
    }
}
