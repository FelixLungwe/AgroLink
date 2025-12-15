<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Dans database/migrations/2025_12_15_XXXXXX_add_boolean_columns_to_products_table.php

    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
        });
    }
};
