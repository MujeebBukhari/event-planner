<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminGalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index(Request $request): View
    {
        $query = GalleryItem::query()->orderBy('sort_order')->orderBy('id', 'desc');

        $status = $request->query('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $search = $request->query('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view('admin.gallery.index', compact('items', 'status', 'search'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create(): View
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created gallery item in storage.
     */
    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = $path;
        }

        $item = GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item \"{$item->title}\" created successfully.");
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(GalleryItem $gallery): View
    {
        return view('admin.gallery.edit', ['item' => $gallery]);
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(UpdateGalleryRequest $request, GalleryItem $gallery): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $path = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = $path;
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item \"{$gallery->title}\" updated successfully.");
    }

    /**
     * Remove the specified gallery item and its image from storage.
     */
    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        $title = $gallery->title;

        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item \"{$title}\" has been deleted.");
    }
}
