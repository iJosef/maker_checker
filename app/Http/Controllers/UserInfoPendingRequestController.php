<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\UserInfoPendingRequest;

class UserInfoPendingRequestController extends Controller
{
    public function fetch_all_pending_requests(Request $request)
    {
        $data = UserInfoPendingRequest::where('created_by', '!=', auth()->user()->id)->where('approved_flag', false)->with('request_type')->get();

        return response()->json($data, 200);
    }


    public function create_new_user_info(Request $request)
    {
        $validated_fields = $request->validate([
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string',
            'request_type'      =>  'required|integer'
        ]);

        $new_user_info = UserInfoPendingRequest::create([
            'first_name'        =>  $validated_fields['first_name'],
            'last_name'         =>  $validated_fields['last_name'],
            'email'             =>  $validated_fields['email'],
            'created_by'        =>  auth()->user()->id,
            'request_type_id'   =>  $validated_fields['request_type'],
        ]);

        // $admin_users = User::select('email')->get();

        // foreach ($admin_users as $admin_user) {
        //     Mail::to($admin_user->email)->send(new ApproveRequest($new_user_info));
        // }

        $data = [
            'status' => 'success',
            'message' => 'New user information created successfully and has been sent for approval'
        ];

        return response()->json($data, 201);

    }


    public function update_user_info(Request $request)
    {
        $validated_fields = $request->validate([
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string',
            'request_type'      =>  'required|integer'
        ]);

        $new_pending_request = UserInfoPendingRequest::create([
            'first_name'        =>  $validated_fields['first_name'],
            'last_name'         =>  $validated_fields['last_name'],
            'email'             =>  $validated_fields['email'],
            'user_info_id'      =>  $request->user_info_id,
            'created_by'        =>  auth()->user()->id,
            'request_type_id'   =>  $validated_fields['request_type'],
        ]);

        // $admin_users = User::select('email')->get();

        // foreach ($admin_users as $admin_user) {
        //     Mail::to($admin_user->email)->send(new ApproveRequest($new_pending_request));
        // }

        $data = [
            'status' => 'success',
            'message' => 'New user information update will be implemented once approved'
        ];

        return response()->json($data, 201);
    }

    public function delete_user_info(Request $request, $id, $request_type)
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

        // $admin_users = User::select('email')->get();

        // foreach ($admin_users as $admin_user) {
        //     Mail::to($admin_user->email)->send(new ApproveRequest($new_pending_request));
        // }

        $data = [
            'status' => 'success',
            'message' => 'User information will be deleted once approved'
        ];

        return response()->json($data, 201);
    }


    public function approve_request(Request $request, $id, $request_type)
    {
        $pending_request = UserInfoPendingRequest::where('id', $id)->first();

        if ($pending_request->created_by != auth()->user()->id) {
            $pending_request->approved_flag = true;
            $pending_request->approved_by = auth()->user()->id;
            $pending_request->update();

            if ($request_type == 1) {

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

            } else if($request_type == 2) {

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

            } else if ($request_type == 3) {
                $user_info = UserInfo::where('id', $pending_request->user_info_id)->delete();

                $data = [
                    'status' => 'success',
                    'message' => 'User Information deleted successfully'
                ];

                return response()->json($data, 200);
            }

        } else {
            $data = [
                'status' => 'error',
                'message' => 'Cannot approve request created by you.'
            ];

            return response()->json($data, 401);
        }

    }


    public function decline_request(Request $request, $id)
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
