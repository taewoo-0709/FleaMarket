<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function showEmailVerificationNotice(Request $request)
    {
        return view('auth.verify');
    }
}
