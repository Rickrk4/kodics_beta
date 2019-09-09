<?php

namespace App;

use App\Http\Resources\ComicCollection;
use App\Http\Resources\DirectoryCollection;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{

    protected $fillable = ['path','name','directory_id', 'cover_id'];

    public function directories(){
        return $this->hasMany('App\Directory');
    }

    public function comics(){
        return $this->hasMany('App\Comic');
    }

    public function parentDir(){
        return $this->belongsTo('App\Directory');
    }

    public function children(){
        return (new DirectoryCollection($this->directories()->get()))->merge(new ComicCollection($this->comics()->get()));
    }

    public function cover(){
        return $this->belongsTo('App\Cover');
    }

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user){
        return $this->users()->find($user->id)->exists();
    }
}
