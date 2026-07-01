<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rtm_rtls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_ami_id')->constrained('audit_amis')->cascadeOnDelete();
            $table->foreignId('auditee_id')->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->date('target_date');
            $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');
            $table->enum('auditor_validation', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rtm_rtls'); }
};