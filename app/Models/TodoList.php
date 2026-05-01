<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    protected $table = 'lists';

    protected $fillable = ['user_id', 'name', 'color', 'list_type', 'is_default', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id');
    }
}
