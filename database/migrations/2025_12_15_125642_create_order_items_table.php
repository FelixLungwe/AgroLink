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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Détails du produit au moment de la commande (au cas où le produit change plus tard)
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->text('product_description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            
            // Options ou variantes
            $table->json('options')->nullable(); // Pour stocker les options comme la taille, la couleur, etc.
            
            // Taxes et réductions
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            
            // Poids et dimensions pour le calcul des frais d'expédition
            $table->decimal('weight', 10, 3)->nullable(); // en kg
            $table->decimal('width', 10, 2)->nullable(); // en cm
            $table->decimal('height', 10, 2)->nullable(); // en cm
            $table->decimal('depth', 10, 2)->nullable(); // en cm
            
            // Statut de l'article (pour le suivi des retours/remboursements)
            $table->string('status')->default('pending'); // pending, shipped, delivered, returned, refunded, cancelled
            
            // Dates importantes
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
        Schema::dropIfExists('order_items');
    }
};
