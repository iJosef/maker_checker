@component('mail::message')

Dear **{{$admin_user->name}},**

This is to notify you that a new request has been made for approval.

Use the button below to view all pending requests.

@component('mail::button', ['url' => url('/api/fetch_all_pending_requests')])
Pending Requests
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

{{-- <!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Approval</title>
</head>
<body>

    <h3>Dear {{ $admin_user->name }},</h3>

    <p>This is to notify you that a new request has been made for approval.</p>
    <p>Use the link below to view all pending requests.</p>
    <p><a href="{{ url('/api/fetch_all_pending_requests') }}">Pending Requests</a></p>

    <p>
        Thanks,<br>
{{ config('app.name') }}
    </p>
</body>
</html> --}}
