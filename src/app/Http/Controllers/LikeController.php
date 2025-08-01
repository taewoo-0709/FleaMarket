<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle($item_id)
    {
        $like = Like::where('user_id', Auth::id())->where('item_id', $item_id)->first();

        if($like) {
            $like->delete();
                return response()->json(['liked' => false]);
        }else {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id,
            ]);
            return response()->json(['liked' =>true]);
        }
    }
}
