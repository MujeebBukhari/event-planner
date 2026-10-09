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
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('payment_method')->index();
            $table->string('stripe_session_id')->nullable()->after('payment_status')->index();
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id')->index();
            $table->timestamp('paid_at')->nullable()->after('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['stripe_session_id']);
            $table->dropIndex(['stripe_payment_intent_id']);
            $table->dropColumn([
                'payment_status',
                'stripe_session_id',
                'stripe_payment_intent_id',
                'paid_at',
            ]);
        });
    }
};
