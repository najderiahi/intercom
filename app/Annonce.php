<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
