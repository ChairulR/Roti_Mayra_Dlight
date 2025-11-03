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
        $q = trim((string) $request->query('q', ''));

        if (Schema::hasTable('breads')) {
            $query = Bread::query();

            if ($q !== '') {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';
                $query->where(function ($builder) use ($like) {
                    $builder->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like);
                });
            }

            $breads = $query->orderBy('created_at', 'desc')->get();
        } else {
            $breads = collect();
        }

        $categories = Schema::hasTable('categories') ? Category::all() : collect();

        return view('frontpage', compact('breads', 'categories'));
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
