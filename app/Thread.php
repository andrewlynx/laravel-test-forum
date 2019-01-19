<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    
    protected $fillable = [
        'title', 'content', 'author', 'parent',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'author');
    }
    
}
