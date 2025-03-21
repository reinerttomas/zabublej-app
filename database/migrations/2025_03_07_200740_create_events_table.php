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
            $table->string('location')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_multi_person')->default(false);
            $table->unsignedInteger('children_count')->nullable();
            $table->unsignedInteger('workers_count')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('reward')->nullable();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('status');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_user');
        Schema::dropIfExists('events');
    }
};
