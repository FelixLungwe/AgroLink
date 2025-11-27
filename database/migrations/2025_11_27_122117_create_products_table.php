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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('producer_id')->constrained('users')->onDelete('cascade');
        $table->string('name');
        $table->text('description');
        $table->decimal('price_kg', 8, 2);        // ex: 3.90
        $table->string('unit')->default('kg');   // kg, pièce, botte, barquette, pot
        $table->integer('stock')->default(0);
        $table->boolean('bio')->default(false);
        $table->boolean('available')->default(true);
        $table->string('photo')->nullable();
        $table->timestamp('harvested_at')->nullable(); // pour "récolté ce matin"
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};