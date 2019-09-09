<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Comic;
use App\Http\Resources\Collection as AppCollection;
use App\Http\Resources\CollectionCollection;
use App\Http\Resources\Collection as ResourceCollection;
use App\Series;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\ErrorHandler\Collecting;

class CollectionController extends Controller
{

    public function __construct()
    {
        //$this->authorizeResource(Collection::class, 'collection');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->get() : Collection::all();
        foreach($collections as $collection){
            $collection['title'] = $collection['name'];
            $collection['type' ] = 'cl';
            $collection['coverUrl'] = "g/".$collection->cover();
        }
        return ['data' => $collections];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        error_log('ciao');
        return 'ok';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        error_log($request->input('title'));
        if($name = $request->input('title')){
            error_log($name);
            $collection = new Collection;
            $collection->name = $name;
            if($user = Auth::user())
                $collection->user_id = $user->id;
            $collection->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collection = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->findOrFail($id) : Collection::findOrFail($id);
        return ['data' => $collection->children()];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collection = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->findOrFail($id) : Collection::findOrFail($id);
        return [
            'data' => [
            'id' => $collection->id,
            'name' => $collection->name,
            'createdBy' => User::find($collection->user_id),
            'children' => $collection->collected()
            ],
            'trashed' => $collection->trashed(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $collection = Collection::findOrFail($id);
        error_log("update collection $id");
        if($name = $request->input('name')){
            $collection->name = $name;
            $collection->save();
            error_log($name);
        }
        if($attach = $request->input('attach')){
            $comics = $attach['comics'];
            $series = $attach['series'];
            error_log("modifico la $id, $comics[0], $series[0]");
            foreach($comics as $comic_id)
                if(($comic = Comic::find($comic_id)) && !$collection->comics()->find($comic_id))
                    $collection->comics()->attach($comic);
            foreach($series as $series_id)
                if(($serie = Series::find($series_id)) && !$collection->series()->find($series_id))
                    $collection->series()->attach($serie);
        } else
        if($detach = $request->input('detach')){
            $comics = $detach['comics'];
            $series = $detach['series'];
            error_log("tolgo la $comics[0], $series[0]");

            foreach($comics as $comic_id)
                if($comic = Comic::find($comic_id))
                    $collection->comics()->detach($comic_id);

            foreach($series as $series_id)
                if($serie = Series::find($series_id))
                    $collection->series()->detach($series_id);
        }

        return $attach['comic'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        error_log("delete $id");
        Collection::onlyTrashed()->forceDelete($id);
        return "$id non esiste più";
    }

    public function destroy($id){
        error_log("soft delete $id");
        Collection::destroy($id);
    }

    public function restore($id){
        error_log("collection $id restored");
        Collection::onlyTrashed()->findOrFail($id)->restore();
        return "$id restored";
    }


}
