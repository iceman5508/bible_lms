<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Auth;

class LanguageController extends Controller
{
    use ImageSaveTrait, ApiStatusTrait;
    protected $model;

    public function __construct(Language $language)
    {
        $this->model = new Crud($language);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_language', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['languages'] = $this->model->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function store(LanguageRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_language', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $language = new Language();
        $language->fill($request->all());
        $language->rtl = $request->rtl == 1 ? 1 : 0;
        if($request->hasFile('flag')){
            $language->flag = $this->saveImage('flag', $request->flag);
        }
        $language->save();

        $path = resource_path('lang/');

        fopen($path."$request->iso_code.json", "w");

        file_put_contents($path."$request->iso_code.json",'{}');

        return $this->success([], __('Language successfully created.'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('manage_language', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $request->validate([
            'language' => 'required'
        ]);

        $language = Language::findOrFail($id);
        $language->language = $request->language;
        $language->rtl = $request->rtl;
        $language->default_language = $request->default_language == 1 ? 1 :0;

        if ($request->hasFile('flag')){
            $language->flag = $this->updateImage('flag', $request->flag, $language->flag);
        }

        $language->save();

        if ($request->default_language == 'on'){
            Language::where('id','!=',$language->id)->update(['default_language' => 'off']);
        }

        $defaultLanguage = Language::where('default_language', 'on')->first();
        if ($defaultLanguage) {
            $ln = $defaultLanguage->iso_code;
            session(['local' => $ln]);
            App::setLocale(session()->get('local'));
        }

        $path = resource_path()."/lang/$language->iso_code.json";

        if(file_exists($path)){
            $file_data = json_encode(file_get_contents($path));
            unlink($path);
            file_put_contents($path,json_decode($file_data));
        }else{
            fopen(resource_path()."/lang/$language->iso_code.json", "w");
            file_put_contents(resource_path()."/lang/$language->iso_code.json", '{}');
        }

        return $this->success([], __('Language successfully Updated.'));
    }

    public function updateLanguage(Request $request,$id)
    {
        if (!Auth::user()->hasPermissionTo('manage_language', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        
        $request->validate([
            'key' => 'required',
            'val' => 'required'
        ]);

        try {
            $language =  Language::findOrFail($id);
            $key = $request->key;
            $val = $request->val;
            $is_new = $request->is_new;
            $path = resource_path()."/lang/$language->iso_code.json";
            $file_data = json_decode(file_get_contents($path),1);

            if(!array_key_exists($key,$file_data)){
                $file_data = array($key=>$val) + $file_data;
            }else if($is_new) {
                return $this->error(['type' => $is_new], __("Already Exist"), 404);
            }else{
                $file_data[$key] = $val;
            }
            unlink($path);

            file_put_contents($path,json_encode($file_data));
            
            return $this->success(['type' => 0], __("Translation Updated"));
        } catch (\Exception $e) {
             return $this->failed([], __("Something went wrong!"));
        }

    }

    public function import(Request $request)
    {
        $language = Language::where('iso_code', $request->import)->firstOrFail();
        $currentLang = Language::where('iso_code', $request->current)->firstOrFail();
        $contents = file_get_contents(resource_path()."/lang/$language->iso_code.json");
        file_put_contents(resource_path()."/lang/$currentLang->iso_code.json",$contents);

        return $this->success([], __('Language Imported Successfully'));
    }
}
