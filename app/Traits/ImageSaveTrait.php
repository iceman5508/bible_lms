<?php


namespace App\Traits;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;
use Intervention\Image\Facades\Image;
use Vimeo\Vimeo;

trait ImageSaveTrait
{
    private function saveImage($destination, $attribute , $width = NULL, $height = NULL): string
    {
        if (!File::isDirectory(base_path().'/public/uploads/'.$destination)){
            File::makeDirectory(base_path().'/public/uploads/'.$destination, 0777, true, true);
        }

        if ($attribute->extension() == 'svg'){
            $file_name = time().Str::random(10).'.'.$attribute->extension();
            $path = 'uploads/'. $destination .'/' .$file_name;
            $attribute->move(public_path('uploads/' . $destination .'/'), $file_name);
            return $path;
        }

        $img = Image::make($attribute);
        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $returnPath = 'uploads/'. $destination .'/' . time().'-'. Str::random(10) . '.' . $attribute->extension();
        $savePath = base_path().'/public/'.$returnPath;
        $img->save($savePath);
        return $returnPath;
    }

    private function updateImage($destination, $new_attribute, $old_attribute , $width = NULL, $height = NULL): string
    {
        if (!File::isDirectory(base_path().'/public/uploads/'.$destination)){
            File::makeDirectory(base_path().'/public/uploads/'.$destination, 0777, true, true);
        }

        if ($new_attribute->extension() == 'svg'){
            $file_name = time().Str::random(10).'.'.$new_attribute->extension();
            $path = 'uploads/'. $destination .'/' .$file_name;
            $new_attribute->move(public_path('uploads/' . $destination .'/'), $file_name);
            File::delete($old_attribute);
            return $path;
        }

        $img = Image::make($new_attribute);
        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $returnPath = 'uploads/'. $destination .'/' . time().'-'. Str::random(10) . '.' . $new_attribute->extension();
        $savePath = base_path().'/public/'.$returnPath;
        $img->save($savePath);
        File::delete($old_attribute);
        return $returnPath;
    }

    /*
     * uploadFile not used
     */
    private function uploadFile($destination, $attribute)
    {
        if (!File::isDirectory(base_path().'/public/uploads/'.$destination)){
            File::makeDirectory(base_path().'/public/uploads/'.$destination, 0777, true, true);
        }

        $file_name = time().Str::random(10).'.'.$attribute->extension();
        $path = 'uploads/'. $destination .'/' .$file_name;

        try {
            if (env('STORAGE_DRIVER') == 's3' ) {
                $data['is_uploaded'] = Storage::disk('s3')->put($path, file_get_contents($attribute->getRealPath()));
            }else if(env('STORAGE_DRIVER') == 'wasabi' ) {
                $data['is_uploaded'] = Storage::disk('wasabi')->put($path, file_get_contents($attribute->getRealPath()));
            }else if(env('STORAGE_DRIVER') == 'vultr' ) {
                $data['is_uploaded'] = Storage::disk('vultr')->put($path, file_get_contents($attribute->getRealPath()));
            } else {
                $attribute->move(public_path('uploads/' . $destination .'/'), $file_name);
            }
        } catch (\Exception $e) {
            //
        }

        return $path;
    }

    private function uploadFileWithDetails($destination, $attribute)
    {
        if (!File::isDirectory(base_path().'/public/uploads/'.$destination)){
            File::makeDirectory(base_path().'/public/uploads/'.$destination, 0777, true, true);
        }

        $data['is_uploaded'] = false;

        if ($attribute == null || $attribute == '') {
            return $data;
        }

        $data['original_filename'] = $attribute->getClientOriginalName();
        $file_name = time().Str::random(10).'.'.pathinfo($data['original_filename'], PATHINFO_EXTENSION);
        $data['path'] = 'uploads/'. $destination .'/' .$file_name;

        try {
            if (env('STORAGE_DRIVER') == 's3' ) {
                $data['is_uploaded'] = Storage::disk('s3')->put($data['path'], file_get_contents($attribute->getRealPath()));
                $data['is_uploaded'] = true;
            }else if(env('STORAGE_DRIVER') == 'wasabi' ) {
                $data['is_uploaded'] = Storage::disk('wasabi')->put($data['path'], file_get_contents($attribute->getRealPath()));
                $data['is_uploaded'] = true;
            }else if(env('STORAGE_DRIVER') == 'vultr' ) {
                $data['is_uploaded'] = Storage::disk('vultr')->put($data['path'], file_get_contents($attribute->getRealPath()));
                $data['is_uploaded'] = true;
            } else {
                $attribute->move(public_path('uploads/' . $destination .'/'), $file_name);
                $data['is_uploaded'] = true;
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return $data;
    }
    
    private function uploadFontInLocal($destination, $attribute, $name)
    {
        if (!File::isDirectory(base_path().'/public/uploads/'.$destination)){
            File::makeDirectory(base_path().'/public/uploads/'.$destination, 0777, true, true);
        }

        $data['is_uploaded'] = false;

        if ($attribute == null || $attribute == '') {
            return $data;
        }

        $data['path'] = 'uploads/'. $destination .'/' .$name;

        try {
            $attribute->move(public_path('uploads/' . $destination .'/'), $name);
            $data['is_uploaded'] = true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return $data;
    }


    private function deleteFile($path)
    {
        if ($path == null || $path == '') {
            return null;
        }

        try {
            if (env('STORAGE_DRIVER') == 's3') {
                Storage::disk('s3')->delete($path);
            } else {
                File::delete($path);
            }
        } catch (\Exception $e) {
            //
        }

        File::delete($path);
    }

    private function deleteVideoFile($path)
    {
        if ($path == null || $path == '') {
            return null;
        }

        try {
            if (env('STORAGE_DRIVER') == 's3') {
                Storage::disk('s3')->delete($path);
            } else {
                File::delete($path);
            }
        } catch (\Exception $e) {
            //
        }

        File::delete($path);

    }

    private function deleteVimeoVideoFile($file)
    {
        if ($file == null || $file == '') {
            return null;
        }

        try {
            $client = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'),env('VIMEO_TOKEN_ACCESS'));
            $path = '/videos/' . $file;
            $client->request($path, [], 'DELETE');
        } catch (\Exception $e)  {
            //
        }
    }

    private function uploadVimeoVideoFile($title, $file)
    {
        $path = '';
        if ($file == null || $file == '') {
            return $path;
        }

        try {
            $client = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'),env('VIMEO_TOKEN_ACCESS'));

            $uri = $client->upload($file, array(
                "name" => $title,
                "description" => "The description goes here."
            ));

            $response = $client->request($uri . '?fields=link');
            $response = $response['body']['link'];

            $str = $response;
            $vimeo_video_id = explode("https://vimeo.com/",$str);
            $path = null;
            if(count($vimeo_video_id))
            {
                $path = $vimeo_video_id[1];
            }
        } catch (\Exception $e) {
            //
        }

        return $path;

    }
}
