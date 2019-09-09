<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $fillable = ['name'];
    public function comics(){
        return $this->belongsToMany('App\Comic');
    }
    public function publisher(){
        return $this->belongsTo('App\Publisher');
    }

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function cover(){
        return $this->belongsTo('App\Cover');
    }

    public function coverUrl(){
        if($cover = $this->cover()->first())
            return $cover->id;
        if($comic = $this->comics()->first())
            return $comic->cover_id;
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user){
        return $this->users()->find($user->id)->exists();
    }

}
