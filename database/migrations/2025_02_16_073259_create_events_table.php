<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_at');
            $table->unsignedInteger('estimated_hours')->nullable();
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->integer('worked_hours')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_user');
        Schema::dropIfExists('events');
    }
};
