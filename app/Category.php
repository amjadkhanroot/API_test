<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // to use softDeletes in db.: deleted from app but remain in db.
    use SoftDeletes;

    // the data allowed to be filled from outside the server.
    protected  $fillable = [
        'user_id',
        'title',
        'description'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return$this->hasMany(Task::class);
    }

    public function comments(){
        return $this->hasManyThrough(Comment::class, Task::class);
    }

    public function files(){
        return $this->hasManyThrough(FileUpload::class, Task::class);
    }
}
