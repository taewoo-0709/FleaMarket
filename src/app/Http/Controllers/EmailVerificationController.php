<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function showEmailVerificationNotice(Request $request)
    {
        $userId = session('registered_user_id');
        $user = $userId ? User::find($userId) : null;

        return view('auth.verify', ['user' => $user]);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            $request->fulfill();
        }

        Auth::login($request->user());

        return redirect()->route('items.list')
            ->with('message', 'メール認証が完了しました。');
    }
}

