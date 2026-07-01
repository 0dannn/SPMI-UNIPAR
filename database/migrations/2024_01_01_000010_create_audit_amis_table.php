<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('audit_amis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_audit_id')->constrained('jadwal_audits')->cascadeOnDelete();
            $table->foreignId('pengukuran_id')->constrained('pengukurans')->cascadeOnDelete();
            $table->integer('auditor_score')->nullable();
            $table->enum('finding_type', ['KTS', 'OB', 'OK'])->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('audit_amis'); }
};