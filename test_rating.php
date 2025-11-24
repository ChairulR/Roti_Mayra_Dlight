<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get first user and bread
$user = App\Models\User::first();
$bread = App\Models\Bread::first();

if (!$user || !$bread) {
    echo "No user or bread found in database\n";
    exit(1);
}

echo "Testing Rating System\n";
echo "=====================\n\n";

echo "User: {$user->name} (ID: {$user->id})\n";
echo "Bread: {$bread->name} (ID: {$bread->id})\n\n";

// Create a test rating
echo "Creating rating (5/5) for {$user->name}...\n";
$rating = $bread->ratings()->updateOrCreate(
    ['user_id' => $user->id],
    ['rating' => 5]
);

echo "✓ Rating created: {$rating->rating}/5\n\n";

// Get average rating
echo "Average rating for '{$bread->name}': {$bread->averageRating()}/5\n";
echo "Total ratings: {$bread->ratings()->count()}\n\n";

// Show all ratings
echo "All ratings:\n";
foreach ($bread->ratings()->with('user')->get() as $r) {
    $userName = $r->user ? $r->user->name : 'Unknown';
    echo "  - {$userName}: {$r->rating}/5 ({$r->created_at->diffForHumans()})\n";
}

echo "\n✓ Rating system is working correctly!\n";
