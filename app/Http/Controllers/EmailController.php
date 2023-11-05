<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CourseMail;
use App\Mail\ContactMail;

class EmailController extends Controller
{
    public function course(Request $request)
    {
        $emailData = $request->all();
        Mail::to('lightdesignestudio@gmail.com')->send(new CourseMail($emailData));
        return response()->json(['message' => 'Email sent successfully']);
    }

    public function contact(Request $request)
    {
        $emailData = [
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'phoneNumber' => $request->input('phoneNumber'),
            'message' => $request->input('message'),
        ];

        Mail::to('lightdesignestudio@gmail.com')->send(new ContactMail($emailData));
        return response()->json(['message' => 'Email sent successfully']);
    }
}
