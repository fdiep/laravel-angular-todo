<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['description','owner_id','is_done'];

    // make sure following fields are returned in correct type
    protected $casts = [
      'id' => 'integer',
      'owner_id' => 'integer',
      'is_done' => 'boolean',
    ];

    /**
     * Get the user that owns this todo.
     */
    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }
}
