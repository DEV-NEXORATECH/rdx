<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('nik', 32)->nullable()->unique()->after('phone');
            $table->date('birth_date')->nullable()->after('nik');
            $table->string('gender', 20)->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('postal_code', 10)->nullable()->after('city');
            $table->string('profile_photo_path')->nullable()->after('postal_code');
            $table->string('ktp_photo_path')->nullable()->after('profile_photo_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['nik']);
            $table->dropColumn([
                'phone', 'nik', 'birth_date', 'gender', 'address', 'city',
                'postal_code', 'profile_photo_path', 'ktp_photo_path',
            ]);
        });
    }
};
