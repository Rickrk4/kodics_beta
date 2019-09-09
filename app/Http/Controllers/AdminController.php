<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Http\Middleware\VerifyCsrfToken;
use App\Jobs\ScannerJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Imtigger\LaravelJobStatus\JobStatus;
use phpDocumentor\Reflection\Types\Object_;

class AdminController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('admin');

    }


    public function index()
    {

        $options = config('settings');
        $settings = array();
        foreach ($options as $key => $value) {
            if(!is_array($value))
                array_push($settings, ['name' => $key, 'value' => $value]);
            //$settings[$key] = $value;
        }
        return ['data' => $settings, 'crsf_token' => Session::token()];
        return null;
        if(!Directory::where('path', config('settings.comics_dir'))->exists())
            Directory::create(['path' => config('settings.comics_dir'),'name'=>'/']);
        $jobStatusId = ScannerJob::make();
        $jobStatus = \App\JobStatus::find($jobStatusId);
        return $jobStatus;
    }

    public function show(Request $request, $option){
        /*
        if($value = $request->input('value')){
            config(["settings.$option" => $value]);
            if($fp = fopen(base_path() .'/config/settings.php' , 'w')){
                fwrite($fp, '<?php return ' . var_export(config('settings'), true) . ';');
                fclose($fp);
            }
        }
        */
        return config("settings.$option");
    }

    public function update(Request $request, $option)
    {
        error_log("update option $option");

        $value = $request->input('value');
        error_log("set to $value");
                config(["settings.$option" => $value]);
        if($fp = fopen(base_path() .'/config/settings.php' , 'w')){
            fwrite($fp, '<?php return ' . var_export(config('settings'), true) . ';');
            fclose($fp);
        }

        if(is_bool($value))
            return $value ? 1 : 0;
        else
            return config("settings.$option");

        if(config("settings.$option") == true)
            return 1;
        error_log("return ".config("settings.$option"));
        if(config("settings.$option") == false)
            return 0;

        return config("settings.$option");

    }

    public function scan(){
        error_log("avvio nuova scansione");
        //return ['id' => 22];
        if(!Directory::where('path', config('settings.comics_dir'))->exists())
            Directory::create(['path' => config('settings.comics_dir'),'name'=>'/']);
        $jobStatusId = ScannerJob::make();
        $jobStatus = \App\JobStatus::find($jobStatusId);
        return $jobStatus;
    }

    public function scanJob($id = null){

        if(is_null($id)){
            return ScannerJob::started() ? ScannerJob::make() : 0;
        }else
        return \App\JobStatus::findOrFail($id);
    }


    public function forceRoload(){
        DB::table(config('queue.connections.database.table'))->truncate();
    }
}
