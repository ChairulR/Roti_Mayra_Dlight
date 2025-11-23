<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FrontPageController extends Controller
{
    public function index(Request $request)
    {
        // Controller sekarang hanya render view, data dihandle oleh Livewire component
        $categories = Schema::hasTable('categories') ? Category::all() : collect();

        // Get top rated products (minimum 1 rating)
        $topRatedProducts = Bread::with(['ratings', 'category'])
            ->whereHas('ratings')
            ->get()
            ->sortByDesc(function ($bread) {
                return $bread->averageRating();
            })
            ->take(6);
        $promotedBreads = Bread::where('is_promoted', true)
            ->limit(3)
            ->get();

        return view('frontpage', compact('categories', 'topRatedProducts', 'promotedBreads'));
    }

    public function show(Bread $bread)
    {
        $bread->load('ratings.user', 'category');
        return view('breads.show', compact('bread'));
    }

    public function about()
    {
        return view('about');
    }
}
