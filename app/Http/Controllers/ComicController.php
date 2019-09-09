<?php

namespace App\Http\Controllers;

use App\ActiveComic;
use App\Author;
use App\Collection;
use App\Comic;
use App\Cover;
use App\Directory;
use App\Http\Resources\Comic as AppComic;
use App\Http\Resources\ComicCollection;
use App\Http\Resources\DirectoryCollection;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\Publisher;
use App\Image;
use App\Libreries\Wrapper\Archiver;
use App\Publisher as AppPublisher;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as Imager;
use wapmorgan\UnifiedArchive\UnifiedArchive;

class ComicController extends Controller
{

    public function __construct()
    {
//        if(config('settings.require_authentication'))
  //          $this->middleware('auth');

//        $this->authorizeResource(Comic::class, 'comic');
    }

    public function index(Request $request)
    {

        if($author_id = $request->input('artist')){
            $comics = Author::findOrFail($author_id)->comics();
            error_log("request comics of author: $author_id");
        } else
        if($publisher_id = $request->input('publisher')){
            $comics = Publisher::findOrFail($publisher_id)->comics();
            error_log("request comics of publisher: $publisher_id");
        } else
        $comics = Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed() : Comic::all();

        if(Auth::user() && Auth::user()->isAdmin())
                $comics = $comics->withTrashed();

        //$comics = Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed() : Comic::all();
        $q = $request->input('q');
        error_log("request comics for $q");
        return new ComicCollection(
            is_null($q) ? $comics->paginate(25) :
                Comic::where('title','like',"%$q%")
                ->orWhere('user_tags', 'like', "%$q%")
                ->orWhereHas('authors', function($query) use ($q){
                    $query->where('authors.name','like',"%$q%");
                })
                ->orWhereHas('publishers', function($query) use($q){
                    $query->where('publishers.name','like',"%$q%");
                })
                ->get()
                //->paginate(25)
        );
    }

    public function show($id)
    {

        if(!($active = ActiveComic::find($id)) && ($comic = Comic::findOrFail($id)) && ($archive = UnifiedArchive::open($comic->file_path))){
            ActiveComic::create(['id' => $id]);
            $archive->extractFiles(gallery_dir.'/'.$id);
            $images = $archive->getFileNames();
            foreach($images as $i=>$image)
                if(pathinfo($image)['extension'] == 'jpg' || pathinfo($image)['extension'] == 'png')
                    Image::create(['comic_id' => $id, 'image_id' => $i, 'image_path' => /*gallery_dir.'/'.*/$id.'/'.$image]);
        }
        $active = ActiveComic::find($id);
        $active->viewd++;
        $active->save();
        return   ['data' =>new ImageCollection($active->images()->get()) /*, 'comic' => new AppComic($active)*/];
    }

    public function update(Request $request, $id){
        error_log("update comic $id");
        $comic = Comic::findOrFail($id);
/*
        if(!is_null($title = $request->input('title'))){
            $comic->update(['title' => $title]);
        }
*/
        if(!is_null($body = $request->input('toUpdate')))
            $comic->update($body);

        if(!is_null($attach = $request->input('attach'))){
            error_log("attach to $id");

            if(!is_null($artists = $attach['authors']))
                foreach($artists as $artist){
                    error_log("attach to $id author $artist");
                    $author = is_string($artist) ? Author::firstOrCreate(['name' => $artist]) : Author::findOrFail($artist);
                    $comic->authors()->attach($author);
                    error_log("attach to $id artist $artist");
                }

            if(!is_null($publishers = $attach['publishers']))
                foreach($publishers as $publisher){
                    error_log("attach to $id publisher $publisher");
                    $publisher = is_string($publisher) ? AppPublisher::firstOrCreate(['name' => $publisher]) : AppPublisher::findOrFail($publisher);
                    $comic->publishers()->attach($publisher);
                    error_log("attach to $id publisher $publisher");
                }
        }

        if(!is_null($detach = $request->input('detach'))){
            if(!is_null($artists = $detach['authors']))
                foreach($artists as $artist){
                    $comic->authors()->detach($artist);
                    error_log("detach to $id artist $artist");
                }
        }

        return 'updated';
    }

    public function edit($id){
        return new AppComic( Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed()->findOrFail($id) : Comic::findOrFail($id));
    }

    public function destroy($id){
        error_log("$id soft deleted");
        Comic::destroy($id);
    }

    public function restore($id){
        error_log("restore $id");
        Comic::onlyTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete($id){
        error_log("$id non esiste più");
        Comic::onlyTrashed()->findOrFail($id)->forceDelete();
    }

    public function defaultCover(){

        return Imager::make(public_path('media/covers.png'))->response();
    }

    public function cover($id)
    {
        return Imager::make(covers_dir.'/'.Cover::findOrFail($id)->cover_path)->response();
    }

    public function page($comicId, $imageId)
    {
        return Imager::make(gallery_dir.'/'.ActiveComic::findOrFail($comicId)->images()->where('image_id',$imageId)->firstOrFail()->image_path)->response();
    }

    public function directories(Request $request, $id = null)
    {
        $items_per_page = 1;
        $page = $request->input('page') ? $request->input('page') : 1;

        //->chunk(2);//[ is_null($request->input('page')) ? 1 : $request->input('page') -1 ];
        $dir = is_null($id) ? Directory::where('name', '/')->firstOrFail() : Directory::findOrFail($id);
        $pages = ceil($dir->children()->count() / $items_per_page);
        $children = $dir->children();

        error_log("request page $page");
        return [
            'data' => $children,
            //'data'=> $dir->children()->chunk($items_per_page, null, false)[$page-1],
            'links' => [
                'current_page' => null,
                'first_page' => 1,
                'last_page' => null,
                'next' => null,
                'prev' => null,
                'parent_dir' => $dir->directory_id
                //'prev' => $page == 1 ? null :  "d/$id?page=".($page-1)
            ]
        ];
    }

}
