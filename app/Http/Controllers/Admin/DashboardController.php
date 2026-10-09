<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\EventBooking;
use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with summary metrics.
     */
    public function index(): View
    {
        $stats = [
            'totalBookings' => EventBooking::count(),
            'pendingBookings' => EventBooking::where('status', 'pending')->count(),
            'totalMessages' => ContactMessage::count(),
            'unreadMessages' => ContactMessage::where('status', 'unread')->count(),
            'totalServices' => Service::count(),
            'totalGalleryItems' => GalleryItem::count(),
            'totalTestimonials' => Testimonial::count(),
        ];

        $recentBookings = EventBooking::latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentMessages'));
    }
}
