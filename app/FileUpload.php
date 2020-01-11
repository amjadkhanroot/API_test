<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    protected $fillable = [
      'name', 'user_id', 'task_id'
    ];

    protected $appends = ['file_url'];

    public function task(){
        return $this->belongsTo(Task::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute (){
        return config('app.url').Storage::url('tasks/'.$this->task_id.'/'.$this->name);
    }
}
