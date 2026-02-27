<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('links')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Dashboard/Tags/Index', [
            'tags' => $tags,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
        ]);

        Tag::create(['name' => $data['name']]);

        return back();
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
        ]);

        $tag->update(['name' => $data['name']]);

        return back();
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back();
    }
}
