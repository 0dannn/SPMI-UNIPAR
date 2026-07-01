<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('komentar_amis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_ami_id')->constrained('audit_amis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('komentar_amis'); }
};