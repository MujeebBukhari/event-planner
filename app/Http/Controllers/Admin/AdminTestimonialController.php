<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminTestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request): View
    {
        $query = Testimonial::query()->orderBy('sort_order')->orderBy('id', 'desc');

        $status = $request->query('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $search = $request->query('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->paginate(15)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials', 'status', 'search'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(StoreTestimonialRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('testimonials', 'public');
            $data['image_path'] = $path;
        }

        $testimonial = Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', "Testimonial from \"{$testimonial->client_name}\" created successfully.");
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            if ($testimonial->image_path && Storage::disk('public')->exists($testimonial->image_path)) {
                Storage::disk('public')->delete($testimonial->image_path);
            }

            $path = $request->file('image')->store('testimonials', 'public');
            $data['image_path'] = $path;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', "Testimonial from \"{$testimonial->client_name}\" updated successfully.");
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $name = $testimonial->client_name;

        if ($testimonial->image_path && Storage::disk('public')->exists($testimonial->image_path)) {
            Storage::disk('public')->delete($testimonial->image_path);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', "Testimonial from \"{$name}\" has been deleted.");
    }
}
