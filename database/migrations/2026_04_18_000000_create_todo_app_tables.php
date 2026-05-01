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
        // 1. lists
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('color', 20)->nullable();
            $table->enum('list_type', ['default', 'grocery'])->default('default');
            $table->boolean('is_default')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        // 2. tasks
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('lists')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('note')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->enum('priority', ['none', 'low', 'medium', 'high'])->default('none');
            $table->enum('status', ['todo', 'done'])->default('todo');
            $table->boolean('is_my_day')->default(false);
            $table->integer('position')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 3. subtasks
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->string('title', 255);
            $table->boolean('is_completed')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps(); // The SQL had only created_at, but timestamps() is Laravel standard.
        });

        // 4. tags
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('color', 20);
            $table->timestamps(); // Adding timestamps for consistency
        });

        // 5. task_tag
        Schema::create('task_tag', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['task_id', 'tag_id']);
        });

        // 6. reminders
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->dateTime('remind_at');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule', 50)->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamps(); // Added for consistency
        });

        // 7. list_shares
        Schema::create('list_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('lists')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['viewer', 'editor'])->default('viewer');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps(); // Added for consistency
        });

        // 8. attachments
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->string('filename', 255);
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->integer('size')->unsigned()->nullable();
            $table->timestamps(); // SQL had created_at, using timestamps()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('list_shares');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('task_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('subtasks');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('lists');
    }
};
