<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Like;
use App\Models\Comment;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequests;
use App\Http\Requests\AddressRequests;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index (Request $request)
    {
      $tab = $request->input('tab', 'recommend');

      if ($tab === 'mylist' && Auth::check()) {
        $items = Auth::user()->likedItems()->latest()->get();
      } else {
        $items = Item::when(Auth::check(), function ($query) {
              return $query->where('user_id', '!=', Auth::id());
                  })
                  ->latest()
                  ->get();
      }

      return view('index', compact('items', 'tab'));
    }
}
