<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 'images'; 
    
    //create a relationship
    public function album() 
    {
        return $this->belongsTo ('App\Album');
    }

}
