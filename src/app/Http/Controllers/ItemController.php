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
      $keyword = $request->input('keyword');

      $items = collect();

      if ($tab === 'mylist' && Auth::check()) {
        $query = Auth::user()->likedItems()->latest();

        if (!empty($keyword)) {
          $query->where('title', 'like', '%' . $keyword . '%');
        }
        $items = $query->get();

      } else {
        $items = Item::when(Auth::check(), function ($query) {
              return $query->where('user_id', '!=', Auth::id());
                  })
                  ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where('title', 'like', '%' . $keyword . '%');
                  })
                  ->latest()
                  ->get();
      }

      return view('index', compact('items', 'tab', 'keyword'));
    }

    public function show()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('exhibition', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
      $item = new Item();

      $item->title = $request->title;
      $item->brand = $request->brand;
      $item->item_explain = $request->item_explain;
      $item->price = $request->price;
      $item->condition_id = $request->condition_id;
      $item->user_id = Auth::id();

      if ($request->hasFile('image_url')) {
          $path = $request->file('image_url')->store('items', 'public');
          $item->image_url = $path;
    }

      $item->save();

      $item->categories()->attach($request->category_id);

    return redirect('/')->with('message', '商品を出品しました');
  }

  public function detailShow($item_id)
  {
      $item = Item::with('likes', 'likedUsers')->findOrFail($item_id);
      return view('item', compact('item'));
  }

  public function postComment(CommentRequest $request, $item_id)
  {
    $comment = new Comment();
    $comment->user_id = Auth::id();
    $comment->item_id = $item_id;
    $comment->comment = $request->input('comment');
    $comment->save();

    return redirect()->back();
  }

  public function toggleLike($item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    if ($user->likedItems->contains($item->id)) {
        $user->likedItems()->detach($item->id);
    } else {
        $user->likedItems()->attach($item->id);
    }
    return back();
  }
}
