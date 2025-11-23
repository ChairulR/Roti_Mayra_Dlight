<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rating;
use App\Models\Category;

class Bread extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'is_promoted',
    ];
    protected $casts = [
        'is_promoted' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function averageRating(): float
    {
        $avg = $this->ratings()->avg('rating');
        return $avg ? round($avg, 2) : 0.0;
    }
}
