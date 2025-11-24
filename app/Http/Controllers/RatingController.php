<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bread;
use App\Models\Rating;

class RatingController extends Controller
{
    /**
     * Store or update a rating for a given bread.
     */
    public function store(Request $request, Bread $bread)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $userId = $request->user()->id;

        // update existing or create new rating for this user on this bread
        $bread->ratings()->updateOrCreate(
            ['user_id' => $userId],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return back()->with('success', 'Terima kasih, rating dan komentar Anda tersimpan.');
    }
}
