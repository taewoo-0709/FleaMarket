<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\CodeVerifyNotification;

class EmailCodeController extends Controller
{
    public function showForm()
    {
        $userId = session('registered_user');
        $user = $userId ? User::find($userId) : null;

        return view('auth.verify_code', ['user' => $user]);
    }

    public function verifyCode(Request $request)
    {
        $entered = implode('', $request->input('code', []));
        $sessionCode = session('verification_code');
        $userId = session('registered_user');

        if ($entered == $sessionCode && $userId) {
            $user = User::find($userId);
            $user->email_verified_at = now();
            $user->save();

            Auth::login($user);
            session()->forget(['verification_code', 'registered_user']);

            return redirect()->route('items.list')->with('message', 'メール認証が完了しました。');
        }

        return back()->with('code_error', '認証コードが違います');
    }

    public function resend(Request $request)
    {
        $userId = session('registered_user');
        $user = $userId ? User::find($userId) : null;

        if ($user) {
            $code = random_int(1000, 9999);
            session(['verification_code' => $code]);
            $user->notify(new CodeVerifyNotification($code));
        }

        return back()->with('message', '認証コードを再送しました');
    }
}
