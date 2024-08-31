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
        Schema::table('user_otp', function (Blueprint $table) {
            $table->integer('is_active')->default(1)->after('otp');
            $table->renameColumn('user_id', 'phone');
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_otp', function (Blueprint $table) {
            $table->dropColumn('otp');
            $table->renameColumn('phone', 'user_id');

        });
    }
};
