<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::with('tags')
            ->latest()
            ->get()
            ->map(fn($link) => [
                'id' => $link->id,
                'name' => $link->name,
                'url' => $link->url,
                'year' => $link->year,
                'is_active' => $link->is_active,
                'tags' => $link->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name]),
            ]);

        $tags = Tag::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Dashboard/Links/Index', [
            'links' => $links,
            'tags' => $tags,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'year' => 'nullable|integer|min:2000|max:2100',
            'is_active' => 'boolean',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $link = Link::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'year' => $data['year'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (!empty($data['tag_ids'])) {
            $link->tags()->sync($data['tag_ids']);
        }

        return back();
    }

    public function update(Request $request, Link $link)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'year' => 'nullable|integer|min:2000|max:2100',
            'is_active' => 'boolean',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $link->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'year' => $data['year'] ?? null,
            'is_active' => $data['is_active'] ?? $link->is_active,
        ]);

        $link->tags()->sync($data['tag_ids'] ?? []);

        return back();
    }

    public function destroy(Link $link)
    {
        $link->delete();
        return back();
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:links,id',
        ]);

        Link::whereIn('id', $request->ids)->delete();

        return back();
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:links,id',
            'year' => 'nullable|integer|min:2000|max:2100',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
            'is_active' => 'nullable|boolean',
        ]);

        $links = Link::whereIn('id', $request->ids)->get();

        foreach ($links as $link) {
            if ($request->has('year')) {
                $link->year = $request->year;
                $link->save();
            }
            if ($request->has('is_active')) {
                $link->is_active = $request->is_active;
                $link->save();
            }
            if ($request->has('tag_ids')) {
                $link->tags()->sync($request->tag_ids);
            }
        }

        return back();
    }
}
