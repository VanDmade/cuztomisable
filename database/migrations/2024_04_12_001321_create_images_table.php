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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->bigInteger('created_from_image_id')->unsigned()->nullable();
            $table->foreign('created_from_image_id')
                ->references('id')
                ->on('images')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->datetime('removed_from_storage_at')->nullable();
            $table->string('name', 64);
            $table->string('extension', 4);
            $table->text('path', 1024);
            $table->string('disk', 64)->default('local');
            $table->text('parameters')->nullable();
            $table->boolean('original')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
