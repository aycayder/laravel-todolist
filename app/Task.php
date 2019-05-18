<?php

namespace todolist;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =
        [
            'name',
            'body',
            'isCompleted',
            'user_id'
        ];
}
