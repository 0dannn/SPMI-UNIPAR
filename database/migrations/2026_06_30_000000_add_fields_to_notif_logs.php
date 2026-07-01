<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->string('type')->default('info')->after('message');
        });
        Schema::table('log_activities', function (Blueprint $table) {
            $table->string('module')->nullable()->after('action');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('log_activities', function (Blueprint $table) {
            $table->dropColumn('module');
        });
    }
};
