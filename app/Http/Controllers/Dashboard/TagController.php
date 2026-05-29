<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = 10;

        $query = Tag::withCount('links');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $total = $query->count();
        $tags = $query->orderBy('name')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Dashboard/Tags/Index', [
            'tags' => $tags,
            'search' => $search,
            'page' => $page,
            'total' => $total,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage),
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
            'name' => 'required|string|max:100|unique:tags,name,'.$tag->id,
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
