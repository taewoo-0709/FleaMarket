<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle($item_id)
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
                ->where('item_id', $item_id)
                ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::firstOrCreate([
                'user_id' => $user->id,
                'item_id' => $item_id,
            ]);
        }

        return redirect()->back();
    }
}