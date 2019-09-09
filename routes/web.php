<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Author;
use App\Comic;
use App\Directory;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\SeriesController;
use App\Http\Resources\ComicCollection;
use App\Image;
use App\Libreries\Chain\Chain;
use App\Libreries\Wrapper\Archiver;
use App\Publisher;
use App\Series;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use wapmorgan\UnifiedArchive\UnifiedArchive;

$scraper = new Chain(config('settings.scrapers'));
/*
function handle($dir = null){

        $files = array_slice(scandir($dir->path),2);
        $directories = [];
        $comics = [];

        foreach($files as $file){
            // $this->setOutput(['file' => ($file = $dir->path.'/'.$file)]);
             $file = $dir->path.'/'.$file;

             if(is_dir($file)){

                 if(!($directory = Directory::where('path',$file)->first())){
                     $dir->directories()->save(($directory = Directory::create(['name' =>  pathinfo($file)['basename'], 'path' => $file])));

                     handle($directory);
                     if(is_null($directory->cover_id)){
                         $directory->cover_id = ($directory->comics() ? $directory->comics() : $directory->directories())->first()->cover_id;
                         $directory->save();
                     }
                 }else
                     handle($directory);
             } else {
                 if(!Comic::where('file_path',$file)->exists() && ($comic = (new Chain(config('settings.scrapers')))->call($file))){
                     $dir->comics()->save($comic);
                 }
            }
    }
}
*/
/*
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');
*/
//if(config('settings.require_authentication'))
    Auth::routes(['register' => config('settings.registration') || !Schema::hasTable('users') || App\User::all()->isEmpty()]);




Route::get('c/restore/{id}', 'ComicController@restore');
Route::delete('c/destroy/{id}', 'ComicController@forceDelete');
Route::get('cl/restore/{id}', 'CollectionController@restore');
Route::delete('cl/destroy/{id}', 'CollectionController@forceDelete');
Route::resource('admin', 'AdminController');
Route::resource('c', 'ComicController');
Route::resource('a', 'AuthorController');
Route::resource('s', 'SeriesController');
Route::resource('p', 'PublisherController');
Route::resource('cl', 'CollectionController');
Route::get('s/{id}', 'SeriesController@show');

// Angular route
Route::get('library', function(){
    //return 'ciao';
    View::addExtension('html','php');
    return view('index');
})->middleware(config('settings.require_authentication') ? 'auth' : null);

Route::get('ad', function(){
    //return 'ciao';
    View::addExtension('html','php');
    return view('admin-portal-index');
})->middleware('auth')->middleware('admin');

/*
Route::get('amminisrazione'. function(){
    View::addExtension('html','php');
    return view('admin-portal-index');
});
*/
Route::get('d', 'ComicController@directories');
Route::get('d/{id}', 'ComicController@directories');
Route::get('g', 'ComicController@defaultCover');
Route::get('g/{comicId}','ComicController@cover');
Route::get('g/{comicId}/{imageId}','ComicController@page');
/*
Route::get('test', function (Request $request) {
    $series_id = (Series::firstOrCreate(['name' => 'Prova']))->id;
    return $series_id;
    $string = "[Prova]The Legend of Zelda - Twilight Princess.cbz";
    $pos = strpos($string, '[');
    error_log($pos);
    if(strpos($string, '[') == 0)
        return substr(strstr($string, ']', true), 1);

    return null;

    $query = DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay());
    $query = DB::table('active_comics');
            foreach($query->get() as $old_comic){
                Image::where('comic_id', $old_comic->id)->delete();
                File::deleteDirectory(gallery_dir.$old_comic->id);
                error_log("cancellare".gallery_dir."/$old_comic->id");
            }

            $query->delete();
            error_log("old comics deleted");
    return null;

    //$query = DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay())->delete();

    //just for test
    $query = DB::table('active_comics');

    foreach($query->get() as $old_comic){
        File::deleteDirectory(gallery_dir.$old_comic->id);
        error_log("cancellare ".gallery_dir.$old_comic->id);
        Image::where('comic_id', $old_comic->id)->delete();
    }
    //return DB::table('active_comics')->get();
    return DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay())->get();
    return Carbon::now()->subDay().'<br>'.Carbon::now();
    return new ComicCollection(Publisher::findOrFail(1)->comics()->get());
    return Comic::find(1)->authors()->get();
    return Comic::whereHas('authors', function($query){
        $query->where('authors.name','=','Artibani');
    })->get();

    return Comic::with(['authors' => function($query){
        $query->where('authors.name','=','Artibani');
    }])->get();
    //return Comic::join('author_comic', 'comics.id','=','author_comic.comic_id')->join('authors','author_id','=','authors.id')->get();

    if($request->input('q'))
        return $request->input('q');
    else
        return 'nessun input passato';
    return $request->all();

    $file = "/home/riccardo/comics/Asterix e i Goti-METAINFO.json";
    if(file_exists($metaFile = $file) && ($metainfo = json_decode(file_get_contents($metaFile))))
    $keys = get_object_vars($metainfo);
    foreach ( $keys as $key => $value){
        echo "$key $value";
    }

});
*/
Route::get('scan', 'AdminController@scan');
Route::get('scanJob', 'AdminController@scanJob');
Route::get('scanJob/{id}', 'AdminController@scanJob');
Route::post('admin/{id}', 'AdminController@store');
/*
Route::get('cccc', function () {
    return Session::token();
});

Route::any('tokenMatch', function(Request $request){
    echo $request->input('_token');

    echo Session::token();
    error_log('boh');
    error_log($request->input('_token'));
    error_log(Session::token());
});
//Route::post('cl', 'CollectionController@store');
*/
Route::get('logout','UserController@logout');
