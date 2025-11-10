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

        return view('frontpage', compact('categories'));
    }

    public function show(\App\Models\Bread $bread)
    {
        return view('breads.show', compact('bread'));
    }

    public function about()
    {
        return view('about');
    }
}
