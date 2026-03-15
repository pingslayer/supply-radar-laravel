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
        Schema::create('disruptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 11, 7);
            $table->string('severity')->default('medium');
            $table->string('source')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
            $table->index(['latitude', 'longitude']);
            $table->index('severity');
            $table->index('reported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disruptions');
    }
};
