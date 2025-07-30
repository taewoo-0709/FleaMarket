<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
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
            'name',
            'postcode',
            'address',
            'building',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile['avatar'] = $path;
        }

        $user->update($profile);

        return redirect('/')->with('message', 'プロフィールを更新しました');
    }

    public function show(Request $request)
    {
        $page = $request->input('page', 'sell');

        if ($page === 'buy') {
            $items = Auth::user()->purchasedItems()->latest()->get();
        } else {
            $items = Auth::user()->items()->latest()->get();
        }

        return view('profile', compact('items', 'page'));
    }
}
