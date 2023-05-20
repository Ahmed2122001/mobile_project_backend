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

        Schema::create('favorite_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('users'); // 'users' is the table name->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('business_service_id');
            $table->foreign('business_service_id')->references('id')->on('business_services')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
        //

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorite_services', function (Blueprint $table) {
            //
        });
    }
};
