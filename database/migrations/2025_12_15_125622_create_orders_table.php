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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->decimal('amount', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            
            // Informations de facturation
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_postal_code');
            $table->string('billing_country');
            
            // Informations de livraison
            $table->string('shipping_name');
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal_code');
            $table->string('shipping_country');
            
            // Informations de paiement
            $table->string('payment_method'); // stripe, paypal, etc.
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_id')->nullable(); // ID de la transaction de paiement
            $table->string('payment_gateway')->nullable(); // stripe, paypal, etc.
            
            // Informations de livraison
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
