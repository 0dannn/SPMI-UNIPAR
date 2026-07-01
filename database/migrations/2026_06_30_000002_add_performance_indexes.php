<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('standars', function (Blueprint $table) {
            $table->index('periode_id');
        });
        Schema::table('jadwal_audits', function (Blueprint $table) {
            $table->index('periode_id');
            $table->index('unit_id');
            $table->index('status');
        });
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->index('unit_id');
        });
        Schema::table('rtm_rtls', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('standars', function (Blueprint $table) {
            $table->dropIndex(['periode_id']);
        });
        Schema::table('jadwal_audits', function (Blueprint $table) {
            $table->dropIndex(['periode_id']);
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['status']);
        });
        Schema::table('pengukurans', function (Blueprint $table) {
            $table->dropIndex(['unit_id']);
        });
        Schema::table('rtm_rtls', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
