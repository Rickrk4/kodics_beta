<?php

namespace App;

use App\Http\Resources\ComicCollection;
use App\Http\Resources\DirectoryCollection;
use App\Http\Resources\SeriesCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{

    use SoftDeletes;

    protected $fillable = ['name', 'user_id'];

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function comics(){
        return $this->morphedByMany('App\Comic', 'collectionable');
    }

    public function directories(){
        return $this->morphedByMany('App\Directory', 'collectionable');
    }

    public function series(){
        return $this->morphedByMany('App\Series', 'collectionable');

    }

    public function cover(){

        if($comic = $this->comics()->first())
            return $comic->cover()->first()->id;
        if($directories = $this->directories()->first())
            return $directories->cover()->first()->id;
        if($series = $this->series()->first())
            return $series->coverUrl();
        if($collection = $this->collections()->first())
            return $collection->coverUrl();


    }

    public function children(){
        return (new SeriesCollection($this->series()->get()))->merge(new ComicCollection($this->comics()->get()));
    }

    public function collected(){
        return [
            "collections" => $this->collections()->get(),
            "series" => new SeriesCollection($this->series()->get()),
            "directories" => new DirectoryCollection($this->directories()->get()),
            "comics" => new ComicCollection( $this->comics()->get())
        ];
    }

    public function createdBy(){
        return $this->hasOne('App\User');
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user){
        return $this->users()->find($user->id)->exists();
    }
}
