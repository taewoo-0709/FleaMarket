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

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ユーザーが見つかりません');
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'メール認証リンクが無効です');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Auth::login($user);
        session()->forget('registered_user_id');

        return redirect()->route('items.list')->with('message', 'メール認証が完了しました。');
    }
}