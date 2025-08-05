<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailSendController extends Controller
{
    public function index()
    {
        $data = [];

        Mail::send('emails.test', $data, function($message){
            $message->to('test@example.com', 'Test')
            ->subject('This is a test mail');
        });

        return view('mail');
    }
}
