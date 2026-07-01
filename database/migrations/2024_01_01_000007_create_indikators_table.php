<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standar_id')->constrained('standars')->cascadeOnDelete();
            $table->enum('type', ['IKU', 'IKT']);
            $table->text('description');
            $table->integer('target');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('indikators'); }
};