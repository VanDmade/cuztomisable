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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->text('to');
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            $table->string('from', 128);
            $table->string('subject', 512);
            $table->text('parameters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
