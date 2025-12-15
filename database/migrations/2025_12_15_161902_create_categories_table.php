<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_column')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add some sample categories
        $categories = [
            ['name' => 'Fruits', 'slug' => 'fruits', 'description' => 'Fruits frais de saison'],
            ['name' => 'Légumes', 'slug' => 'legumes', 'description' => 'Légumes frais de saison'],
            ['name' => 'Produits Laitiers', 'slug' => 'produits-laitiers', 'description' => 'Fromages, yaourts et autres produits laitiers'],
            ['name' => 'Viandes', 'slug' => 'viandes', 'description' => 'Viandes fraîches et produits carnés'],
            ['name' => 'Boulangerie', 'slug' => 'boulangerie', 'description' => 'Pains et viennoiseries'],
            ['name' => 'Boissons', 'slug' => 'boissons', 'description' => 'Jus, vins et autres boissons'],
            ['name' => 'Épicerie', 'slug' => 'epicerie', 'description' => 'Produits d\'épicerie divers'],
            ['name' => 'Produits Bio', 'slug' => 'bio', 'description' => 'Tous nos produits biologiques'],
        ];

        foreach ($categories as $index => $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'is_active' => true,
                'order_column' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
