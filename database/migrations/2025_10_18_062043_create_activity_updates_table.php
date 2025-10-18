<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('activity_updates', function (Blueprint $table) {
        $table->id();
        $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('date'); 
        $table->enum('status', ['Pending', 'Done']);
        $table->text('remark')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('activity_updates');
    }
};
