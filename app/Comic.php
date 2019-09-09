<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comic extends Model
{
    use SoftDeletes;
    protected $fillable = ['file_path', 'title', 'cover_id'];

    public function isActive()
    {
        $this->hasOne('App\ActiveComic')->exists();
    }

    public function activeComic(){
        return $this->hasOne('App\ActiveComic','comic_id');
    }

    public function images()
    {
        return $this->hasManyThrough('App\Image', 'App\ActiveComic');
    }

    public function parentDir(){
        return $this->belongsTo('App\Directory');
    }

    public function cover(){
        return $this->belongsTo('App\Cover');
    }

    public function authors(){
        return $this->belongsToMany('App\Author')->withPivot('role');
    }

    public function series(){
        return $this->belongsToMany('App\Series');
    }

    public function publishers(){
        return $this->belongsToMany('App\Publisher');
    }

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function readableBy($user){
        return true;
        return $this->users()->find($user->id)->exists();
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable')->concat($this->hasManyThrough('App\User','App\Group'));
    }

}
