<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use File;
use Auth;


class LanguageController extends Controller
{
    use ImageSaveTrait, General;
    protected $model;

    public function __construct(Language $language)
    {
        $this->model = new Crud($language);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Language';
        $data['languages'] = $this->model->getOrderById('DESC', 25);
        return view('admin.language.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Language';
        return view('admin.language.create', $data);
    }

    public function store(LanguageRequest $request)
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $language = new Language();
        $language->fill($request->all());
        $language->rtl = $request->rtl ? 1 : 0;
        if($request->hasFile('flag')){
            $language->flag = $this->saveImage('flag', $request->flag);

        }
        $language->save();

        $path = resource_path('lang/');

        fopen($path."$request->iso_code.json", "w");

        file_put_contents($path."$request->iso_code.json",'{}');

        $this->showToastrMessage('success', __('Language successfully created.'));
        return redirect()->route('language.translate', [$language->id]);
    }

    public function edit($id)
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Language';
        $data['language'] = Language::findOrFail($id);
        return view('admin.language.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'language' => 'required'
        ]);

        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $language = Language::findOrFail($id);
        $language->language = $request->language;
        $language->rtl = $request->rtl;
        $language->default_language = $request->default_language;

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

        $this->showToastrMessage('success', __('Language successfully Updated.'));
        return redirect()->route('language.index');
    }

    public function updateLanguage(Request $request,$id)
    {
        $request->validate([
            'key' => 'required',
            'val' => 'required'
        ]);

        if (!Auth::user()->can('manage_language')) {
            $response['msg'] = __("Permission Denied!");
            $response['status'] = 404;
            return response()->json($response);
        }

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
                $response['msg'] = __("Already Exist");
                $response['status'] = 404;
                $response['type'] = $is_new;
                return response()->json($response);
            }else{
                $file_data[$key] = $val;
            }
            unlink($path);

            file_put_contents($path,json_encode($file_data));

            $response['msg'] = __("Translation Updated");
            $response['status'] = 200;
            $response['type'] = 0;
            return response()->json($response);
        } catch (\Exception $e) {
            $response['msg'] = __("Something went wrong!");
            $response['status'] = 404;
            $response['type'] = 0;
            return response()->json($response);
        }

    }


    public function translateLanguage($id)
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Translate';
        $data['language'] = Language::findOrFail($id);
        $iso_code = $data['language']->iso_code;

        $path = resource_path()."/lang/$iso_code.json";
        if(!file_exists($path)){
            fopen(resource_path()."/lang/$iso_code.json", "w");
            file_put_contents(resource_path()."/lang/$iso_code.json", '{}');
        }

        $data['translators'] = json_decode(file_get_contents(resource_path()."/lang/$iso_code.json"),true);
        $data['languages'] = Language::where('iso_code','!=', $iso_code)->get();

        return view('admin.language.translate', $data);
    }


    public function updateTranslate(Request $request, $id)
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        try {
            $language =  Language::findOrFail($id);
            $keyArray = array();
            foreach ($request->key ?? [] as $value)
            {

                $keyArr = explode(' ', $value);
                foreach ($keyArr as $word){
                    if(!str_contains($word, '_')){
                        $strLowerWord = strtolower($word);
                        $modifiedWord = ucwords(trim($strLowerWord));
                        $value = str_replace($word,$modifiedWord,$value);
                    }
                }

                array_push($keyArray, $value);
            }

            $translator = array_filter(array_combine($keyArray, $request->value));
            file_put_contents(resource_path()."/lang/$language->iso_code.json",json_encode($translator));
            $this->showToastrMessage('success', __('Save Successfully'));

        } catch (\Exception $e) {
            $this->showToastrMessage('error', __('Something went wrong!'));
        }


        return redirect()->back();
    }

    public function delete($id)
    {
        if (!Auth::user()->can('manage_language')) {
            abort('403');
        } // end permission checking

        if ($id == 1){
            $this->showToastrMessage('warning', __('You Cannot delete this language.'));
            return redirect()->back();
        }

        $lang =  Language::findOrFail($id);
        $path = resource_path()."/lang/$lang->iso_code.json";
        if(file_exists($path)){
            @unlink($path);
        }

        $lang->delete();

        $language = Language::find(1);
        if ($language) {
            $ln = $language->iso_code;
            session(['local' => $ln]);
            App::setLocale(session()->get('local'));
        }


        $this->showToastrMessage('success', __('Language successfully deleted'));
        return redirect()->back();
    }

    public function import(Request $request)
    {
        $language = Language::where('iso_code', $request->import)->firstOrFail();
        $currentLang = Language::where('iso_code', $request->current)->firstOrFail();
        $contents = file_get_contents(resource_path()."/lang/$language->iso_code.json");
        file_put_contents(resource_path()."/lang/$currentLang->iso_code.json",$contents);

        $this->showToastrMessage('success', __('Language Updated Successfully'));
        return redirect()->back();
    }
}
