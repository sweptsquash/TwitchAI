<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_suggestions', function (Blueprint $table) {
            $table->id();

            $table->text('activity');
            $table->string('category');
            $table->string('language');

            $table->json('response')->nullable();

            $table->timestamps();
        });
    }
};
