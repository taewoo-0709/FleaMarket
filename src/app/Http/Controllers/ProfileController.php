<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile_update', compact('user'));
    }

    public function update(ProfileRequest $request)
{
    $user = Auth::user();

    $profile = $request->only([
        'avatar',
        'name',
        'postcode',
        'address',
        'building',
    ]);

    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $profile['avatar'] = $path;
    }

        $user->update($profile);

        return redirect('/')->with('message', 'プロフィールを更新しました');
    }
}
