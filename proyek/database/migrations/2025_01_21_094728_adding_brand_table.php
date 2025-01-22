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
        //
        Schema::create('brands', function (Blueprint $table) {
            $table->id("ID_brands")->autoIncrement();
            $table->string("name")->unique();
            $table->string("logo");
            $table->text("description")->nullable();
            $table->boolean("premium")->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
