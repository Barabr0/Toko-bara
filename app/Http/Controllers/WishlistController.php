<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $products = auth()->user()->wishlists()
            ->with(['category', 'primaryImage'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->hasInWishlist($product)) {
            $user->wishlists()->detach($product->id);

            $added = false;
            $message = 'Produk dihapus dari wishlist.';
        } else {
            $user->wishlists()->attach($product->id);

            $added = true;
            $message = 'Produk ditambahkan ke wishlist!';
        }

        return response()->json([
            'status' => 'success',
            'added' => $added,
            'message' => $message,
            'count' => $user->wishlists()->count() 
        ]);
    }
}
