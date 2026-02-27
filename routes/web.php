<?php

use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\ProfileController;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $links = Link::with('tags')
        ->where('is_active', true)
        ->latest()
        ->get()
        ->map(fn($link) => [
            'id' => $link->id,
            'name' => $link->name,
            'url' => $link->url,
            'year' => $link->year,
            'tags' => $link->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name]),
        ]);

    $years = Link::where('is_active', true)
        ->whereNotNull('year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    $tags = Tag::orderBy('name')->get(['id', 'name']);

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'links' => $links,
        'years' => $years,
        'tags' => $tags,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/links/bulk-destroy', [LinkController::class, 'bulkDestroy'])->name('links.bulk-destroy');
    Route::post('/links/bulk-update', [LinkController::class, 'bulkUpdate'])->name('links.bulk-update');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
