<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'list_id', 'user_id', 'title', 'note', 'due_date',
        'priority', 'status', 'is_my_day', 'position', 'completed_at',
    ];

    protected $casts = [
        'is_my_day'    => 'boolean',
        'completed_at' => 'datetime',
        'due_date'     => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function list()
    {
        return $this->belongsTo(TodoList::class, 'list_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class)->orderBy('position');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class)->orderBy('created_at', 'desc');
    }
}
