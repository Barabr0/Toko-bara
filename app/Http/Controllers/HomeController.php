<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::query()
            ->active()                    
            ->withCount(['activeProducts' => function($q) {
                $q->where('is_active', true)
                  ->where('stock', '>', 0);
            }])
            ->having('active_products_count', '>', 0)
            ->orderBy('name')
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()
            ->inStock()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
    }
}
