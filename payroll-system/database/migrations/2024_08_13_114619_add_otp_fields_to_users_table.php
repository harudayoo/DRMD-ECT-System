<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at', 'otp_verified_at']);
        });
    }
};
