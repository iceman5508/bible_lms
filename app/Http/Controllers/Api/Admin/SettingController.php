<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait;

    public function GeneralSettingUpdate(Request $request)
    {
        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            if ($request->hasFile('app_logo') && $key == 'app_logo') {
                $request->validate([
                    'app_logo' => 'mimes:png,svg|file'
                ]);
                $this->deleteFile(get_option('app_logo'));
                $option->option_value = $this->saveImage('setting', $request->app_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('app_fav_icon') && $key == 'app_fav_icon') {
                $request->validate([
                    'app_fav_icon' => 'mimes:png,svg|file'
                ]);
                $this->deleteFile(get_option('app_fav_icon'));
                $option->option_value = $this->saveImage('setting', $request->app_fav_icon, null, null);
                $option->save();
            } elseif ($request->hasFile('app_pwa_icon') && $key == 'app_pwa_icon') {
                $request->validate([
                    'app_pwa_icon' => 'mimes:png|file|dimensions:width=512,height=512'
                ]);
                $this->deleteFile(get_option('app_pwa_icon'));
                $option->option_value = $this->saveImage('setting', $request->app_pwa_icon, null, null);
                $option->save();
            } elseif ($request->hasFile('app_preloader') && $key == 'app_preloader') {
                $request->validate([
                    'app_preloader' => 'mimes:png,svg|file'
                ]);
                $this->deleteFile(get_option('app_preloader'));
                $option->option_value = $this->saveImage('setting', $request->app_preloader, null, null);
                $option->save();
            } elseif ($request->hasFile('faq_image') && $key == 'faq_image') {
                $request->validate([
                    'faq_image' => 'mimes:png,jpg,jpeg|file|dimensions:min_width=650,min_height=650,max_width=650,max_height=650'
                ]);
                $this->deleteFile('faq_image');
                $option->option_value = $this->saveImage('setting', $request->faq_image, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_first_logo') && $key == 'home_special_feature_first_logo') {
                $request->validate([
                    'home_special_feature_first_logo' => 'mimes:png|file|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_first_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_first_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_second_logo') && $key == 'home_special_feature_second_logo') {
                $request->validate([
                    'home_special_feature_second_logo' => 'mimes:png|file|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_second_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_second_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_third_logo') && $key == 'home_special_feature_third_logo') {
                $request->validate([
                    'home_special_feature_third_logo' => 'mimes:png|file|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_third_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_third_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('course_logo') && $key == 'course_logo') {
                $request->validate([
                    'course_logo' => 'mimes:png|file|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('course_logo'));
                $option->option_value = $this->saveImage('setting', $request->course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('bundle_course_logo') && $key == 'bundle_course_logo') {
                $request->validate([
                    'bundle_course_logo' => 'mimes:png|file|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('bundle_course_logo'));
                $option->option_value = $this->saveImage('setting', $request->bundle_course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('top_category_logo') && $key == 'top_category_logo') {
                $request->validate([
                    'top_category_logo' => 'mimes:png|file|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('top_category_logo'));
                $option->option_value = $this->saveImage('setting', $request->top_category_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('top_instructor_logo') && $key == 'top_instructor_logo') {
                $request->validate([
                    'top_instructor_logo' => 'mimes:png|file|dimensions:min_width=70,min_height=70,max_width=70,max_height=70'
                ]);
                $this->deleteFile(get_option('top_instructor_logo'));
                $option->option_value = $this->saveImage('setting', $request->top_instructor_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('become_instructor_video_logo') && $key == 'become_instructor_video_logo') {
                $request->validate([
                    'become_instructor_video_logo' => 'mimes:png|file|dimensions:min_width=70,min_height=70,max_width=70,max_height=70'
                ]);
                $this->deleteFile(get_option('become_instructor_video_logo'));
                $option->option_value = $this->saveImage('setting', $request->become_instructor_video_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_logo') && $key == 'customer_say_logo') {
                $request->validate([
                    'customer_say_logo' => 'mimes:png|file|dimensions:min_width=64,min_height=64,max_width=64,max_height=64'
                ]);
                $this->deleteFile(get_option('customer_say_logo'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_first_logo') && $key == 'achievement_first_logo') {
                $request->validate([
                    'achievement_first_logo' => 'mimes:png|file|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_first_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_first_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_second_logo') && $key == 'achievement_second_logo') {
                $request->validate([
                    'achievement_second_logo' => 'mimes:png|file|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_second_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_second_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_third_logo') && $key == 'achievement_third_logo') {
                $request->validate([
                    'achievement_third_logo' => 'mimes:png|file|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_third_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_third_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_four_logo') && $key == 'achievement_four_logo') {
                $request->validate([
                    'achievement_four_logo' => 'mimes:png|file|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_four_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_four_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('sign_up_left_image') && $key == 'sign_up_left_image') {
                $request->validate([
                    'sign_up_left_image' => 'mimes:png,svg|file'
                ]);
                $this->deleteFile(get_option('sign_up_left_image'));
                $option->option_value = $this->saveImage('setting', $request->sign_up_left_image, null, null);
                $option->save();
            } elseif ($request->hasFile('become_instructor_video_preview_image') && $key == 'become_instructor_video_preview_image') {
                $request->validate([
                    'become_instructor_video_preview_image' => 'mimes:png|file|dimensions:min_width=835,min_height=630,max_width=835,max_height=630'
                ]);
                $this->deleteFile(get_option('become_instructor_video_preview_image'));
                $option->option_value = $this->saveImage('setting', $request->become_instructor_video_preview_image, null, null);
                $option->save();
            } elseif ($request->hasFile('become_instructor_video') && $key == 'become_instructor_video') {
                $this->deleteVideoFile(get_option('become_instructor_video'));

                $file_details = $this->uploadFileWithDetails('setting', $request->become_instructor_video);
                if (!$file_details['is_uploaded']) {
                    $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                    return redirect()->back();
                }

                $option->option_value = $file_details['path'];
                $option->save();
            } elseif ($request->hasFile('certificate_font') && $key == 'certificate_font') {
                $this->deleteVideoFile(get_option('certificate_font'));

                $file_details = $this->uploadFontInLocal('setting', $request->certificate_font, 'certificate_font.ttf');
                if (!$file_details['is_uploaded']) {
                    $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                    return redirect()->back();
                }

                $option->option_value = $file_details['path'];
                $option->save();
            } elseif ($key == 'TIMEZONE') {
              
                setEnvironmentValue('TIMEZONE', $request->TIMEZONE);

                $option->option_value = $request->TIMEZONE;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        if ($request->currency_id) {
            Currency::where('id', $request->currency_id)->update(['current_currency' => 'on']);
            Currency::where('id', '!=', $request->currency_id)->update(['current_currency' => 'off']);
        }

        /**  ====== Set Language ====== */
        if ($request->language_id) {
            Language::where('id', $request->language_id)->update(['default_language' => 'on']);
            Language::where('id', '!=', $request->language_id)->update(['default_language' => 'off']);
            $language = Language::where('default_language', 'on')->first();
        }

        Artisan::call('optimize:clear');

        if(get_option('pwa_enable')){
            updateManifest();
        }

        return $this->success([], __('Successfully update'));
    }

    public function storageSettingsUpdate(Request $request)
    {
        $values['STORAGE_DRIVER'] = $request->STORAGE_DRIVER;
        $values['AWS_ACCESS_KEY_ID'] = $request->AWS_ACCESS_KEY_ID;
        $values['AWS_SECRET_ACCESS_KEY'] = $request->AWS_SECRET_ACCESS_KEY;
        $values['AWS_DEFAULT_REGION'] = $request->AWS_DEFAULT_REGION;
        $values['AWS_BUCKET'] = $request->AWS_BUCKET;

        $values['WASABI_ACCESS_KEY_ID'] = $request->WASABI_ACCESS_KEY_ID;
        $values['WASABI_SECRET_ACCESS_KEY'] = $request->WASABI_SECRET_ACCESS_KEY;
        $values['WASABI_DEFAULT_REGION'] = $request->WASABI_DEFAULT_REGION;
        $values['WASABI_BUCKET'] = $request->WASABI_BUCKET;

        $values['VULTR_ACCESS_KEY_ID'] = $request->VULTR_ACCESS_KEY_ID;
        $values['VULTR_SECRET_ACCESS_KEY'] = $request->VULTR_SECRET_ACCESS_KEY;
        $values['VULTR_DEFAULT_REGION'] = $request->VULTR_DEFAULT_REGION;
        $values['VULTR_BUCKET'] = $request->VULTR_BUCKET;

        if (!updateEnv($values)) {
            return $this->failed([], __(SWR));
        }

        Artisan::call('optimize:clear');
        
        return $this->success([], __('Successfully update'));
    }

    public function saveSetting(Request $request)
    {
        $this->updateSettings($request);
        $this->showToastrMessage('success', __('Successfully Saved'));
        return redirect()->back();
    }

    private function updateSettings($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }
        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
            setEnvironmentValue($key, $value);
        }

        Artisan::call('optimize:clear');
    }
}
