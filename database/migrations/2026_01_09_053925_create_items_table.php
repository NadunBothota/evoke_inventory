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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('item_user');
            $table->string('device_name');
            $table->string('department');
            $table->string('reference_number');
            $table->decimal('value', 10, 2);
            $table->enum('status', ['working', 'not_working', 'misplaced'])
            ->default('working');
            $table->string('photo')->nullable();
            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
