<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\StripeWebhookController;
use App\Models\EventSetting;
use App\Models\GalleryItem;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $services = Service::where('is_active', true)->orderBy('sort_order')->get();
    $galleryItems = GalleryItem::where('is_active', true)->orderBy('sort_order')->get();
    $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();
    $event = EventSetting::current();

    return view('home', compact('services', 'galleryItems', 'testimonials', 'event'));
})->name('home');

Route::get('/about', function () {
    $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();

    return view('about', compact('testimonials'));
})->name('about');

Route::get('/solutions', function () {
    $services = Service::where('is_active', true)->orderBy('sort_order')->get();

    return view('solutions', compact('services'));
})->name('solutions');

Route::get('/work', function () {
    $galleryItems = GalleryItem::where('is_active', true)->orderBy('sort_order')->get();

    return view('work', compact('galleryItems'));
})->name('work');

Route::get('/tickets', function () {
    $event = EventSetting::current();

    return view('tickets', compact('event'));
})->name('tickets');

Route::post('/tickets', [EventBookingController::class, 'store'])->name('tickets.store');
Route::get('/tickets/success', [EventBookingController::class, 'success'])->name('tickets.success');
Route::get('/tickets/cancel', [EventBookingController::class, 'cancel'])->name('tickets.cancel');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::get('/insights', function () {
    return view('insights');
})->name('insights');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Global Password Reset Routes (matches Laravel notification default)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('login.submit');
        Route::post('/login/store', [AuthController::class, 'store'])->name('login.store');

        Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        // Featured Event & Ticket Pricing
        Route::get('/event', [AdminEventController::class, 'edit'])->name('event.edit');
        Route::put('/event', [AdminEventController::class, 'update'])->name('event.update');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

        // Contact Messages
        Route::get('/contacts', [AdminContactMessageController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactMessageController::class, 'show'])->name('contacts.show');
        Route::patch('/contacts/{contact}/status', [AdminContactMessageController::class, 'updateStatus'])->name('contacts.status');
        Route::delete('/contacts/{contact}', [AdminContactMessageController::class, 'destroy'])->name('contacts.destroy');

        // Services
        Route::resource('services', AdminServiceController::class)->except(['show']);
        Route::patch('/services/{service}/toggle', [AdminServiceController::class, 'toggleStatus'])->name('services.toggle');

        // Gallery
        Route::resource('gallery', AdminGalleryController::class)->except(['show'])->parameters(['gallery' => 'gallery']);

        // Testimonials
        Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);
    });
});
