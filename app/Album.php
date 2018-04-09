<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';

    protected $fillable = array('name','description','cover_image');

    public function Photos() {
        return $this->has_many('images');
    }
}
