<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->default('Upcoming Event');
            $table->string('title')->default('RK Vibes Presents — Fall Gathering 2026');
            $table->string('date_location')->default('Minneapolis, MN · Oct 2026');
            $table->string('doors_time')->nullable()->default('Doors 6:00 PM');
            $table->decimal('general_admission_price', 10, 2)->default(25.00);
            $table->decimal('vip_access_price', 10, 2)->default(60.00);
            $table->string('general_admission_desc')->nullable()->default('Standing room, full event access');
            $table->string('vip_access_desc')->nullable()->default('Front section, early entry, meet & greet');
            $table->string('cta_text')->default('Book Now — Secure Checkout');
            $table->string('note')->nullable()->default('Paid direct via Stripe — no third-party ticketing fees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};
