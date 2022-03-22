<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\UserInfoPendingRequest;

class UserInfoPendingRequestController extends Controller
{
    public function fetch_all_pending_requests(Request $request)
    {
        $data = UserInfoPendingRequest::where('created_by', '!=', auth()->user()->id)->with('request_type')->get();

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
}
