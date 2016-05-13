<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['description','owner_id','is_done'];

    /**
     * Get the user that owns this todo.
     */
    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }
}
