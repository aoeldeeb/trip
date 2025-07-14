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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('email');
            $table->foreignId('agent_id')->nullable()->after('phone')->constrained()->onDelete('set null');
            $table->enum('language', ['ar', 'en', 'ru'])->default('en')->after('agent_id');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'agent_id', 'language']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
