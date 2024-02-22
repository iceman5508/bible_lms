<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\Currency;
use App\Models\FaqQuestion;
use App\Models\InstructorFeature;
use App\Models\InstructorProcedure;
use App\Models\Language;
use App\Models\Meta;
use App\Models\Package;
use App\Models\Setting;
use App\Models\SupportTicketQuestion;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Spatie\Sitemap\SitemapGenerator;
use Response;
use File;

class SettingController extends Controller
{
    use General, ImageSaveTrait;

    protected $metaModel;

    public function __construct(Meta $meta)
    {
        $this->metaModel = new Crud($meta);
    }

    public function GeneralSetting()
    {
        if (!Auth::user()->can('global_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'General Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['generalSettingsActiveClass'] = 'active';
        $data['currencies'] = Currency::all();
        $data['current_currency'] = Currency::where('current_currency', 'on')->first();
        $data['languages'] = Language::all();
        $data['default_language'] = Language::where('default_language', 'on')->first();

        return view('admin.application_settings.general.general-settings', $data);
    }

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
                    'app_logo' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('app_logo'));
                $option->option_value = $this->saveImage('setting', $request->app_logo, null, null);
                $option->save();
            }elseif ($request->hasFile('app_black_logo') && $key == 'app_black_logo') {
                $request->validate([
                    'app_black_logo' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('app_black_logo'));
                $option->option_value = $this->saveImage('setting', $request->app_black_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('app_fav_icon') && $key == 'app_fav_icon') {
                $request->validate([
                    'app_fav_icon' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('app_fav_icon'));
                $option->option_value = $this->saveImage('setting', $request->app_fav_icon, null, null);
                $option->save();
            } elseif ($request->hasFile('app_footer_payment_image') && $key == 'app_footer_payment_image') {
                $request->validate([
                    'app_footer_payment_image' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('app_footer_payment_image'));
                $option->option_value = $this->saveImage('setting', $request->app_footer_payment_image, null, null);
                $option->save();
            } elseif ($request->hasFile('app_pwa_icon') && $key == 'app_pwa_icon') {
                $request->validate([
                    'app_pwa_icon' => 'mimes:png|dimensions:width=512,height=512'
                ]);
                $this->deleteFile(get_option('app_pwa_icon'));
                $option->option_value = $this->saveImage('setting', $request->app_pwa_icon, null, null);
                $option->save();
            } elseif ($request->hasFile('app_preloader') && $key == 'app_preloader') {
                $request->validate([
                    'app_preloader' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('app_preloader'));
                $option->option_value = $this->saveImage('setting', $request->app_preloader, null, null);
                $option->save();
            } elseif ($request->hasFile('faq_image') && $key == 'faq_image') {
                $request->validate([
                    'faq_image' => 'mimes:png,jpg,jpeg|dimensions:min_width=650,min_height=650,max_width=650,max_height=650'
                ]);
                $this->deleteFile('faq_image');
                $option->option_value = $this->saveImage('setting', $request->faq_image, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_first_logo') && $key == 'home_special_feature_first_logo') {
                $request->validate([
                    'home_special_feature_first_logo' => 'mimes:png|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_first_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_first_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_second_logo') && $key == 'home_special_feature_second_logo') {
                $request->validate([
                    'home_special_feature_second_logo' => 'mimes:png|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_second_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_second_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('home_special_feature_third_logo') && $key == 'home_special_feature_third_logo') {
                $request->validate([
                    'home_special_feature_third_logo' => 'mimes:png|dimensions:min_width=77,min_height=77,max_width=77,max_height=77'
                ]);
                $this->deleteFile(get_option('home_special_feature_third_logo'));
                $option->option_value = $this->saveImage('setting', $request->home_special_feature_third_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('course_logo') && $key == 'course_logo') {
                $request->validate([
                    'course_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('course_logo'));
                $option->option_value = $this->saveImage('setting', $request->course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('product_section_logo') && $key == 'product_section_logo') {
                $request->validate([
                    'product_section_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('product_section_logo'));
                $option->option_value = $this->saveImage('setting', $request->product_section_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('category_course_logo') && $key == 'category_course_logo') {
                $request->validate([
                    'category_course_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('category_course_logo'));
                $option->option_value = $this->saveImage('setting', $request->category_course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('upcoming_course_logo') && $key == 'upcoming_course_logo') {
                $request->validate([
                    'upcoming_course_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('upcoming_course_logo'));
                $option->option_value = $this->saveImage('setting', $request->upcoming_course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('bundle_course_logo') && $key == 'bundle_course_logo') {
                $request->validate([
                    'bundle_course_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('bundle_course_logo'));
                $option->option_value = $this->saveImage('setting', $request->bundle_course_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('top_category_logo') && $key == 'top_category_logo') {
                $request->validate([
                    'top_category_logo' => 'mimes:png|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
                ]);
                $this->deleteFile(get_option('top_category_logo'));
                $option->option_value = $this->saveImage('setting', $request->top_category_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('top_instructor_logo') && $key == 'top_instructor_logo') {
                $request->validate([
                    'top_instructor_logo' => 'mimes:png|dimensions:min_width=70,min_height=70,max_width=70,max_height=70'
                ]);
                $this->deleteFile(get_option('top_instructor_logo'));
                $option->option_value = $this->saveImage('setting', $request->top_instructor_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('become_instructor_video_logo') && $key == 'become_instructor_video_logo') {
                $request->validate([
                    'become_instructor_video_logo' => 'mimes:png|dimensions:min_width=70,min_height=70,max_width=70,max_height=70'
                ]);
                $this->deleteFile(get_option('become_instructor_video_logo'));
                $option->option_value = $this->saveImage('setting', $request->become_instructor_video_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_logo') && $key == 'customer_say_logo') {
                $request->validate([
                    'customer_say_logo' => 'mimes:png|dimensions:min_width=64,min_height=64,max_width=64,max_height=64'
                ]);
                $this->deleteFile(get_option('customer_say_logo'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_first_image') && $key == 'customer_say_first_image') {
                $request->validate([
                    'customer_say_first_image' => 'mimes:png'
                ]);
                $this->deleteFile(get_option('customer_say_first_image'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_first_image, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_second_image') && $key == 'customer_say_second_image') {
                $request->validate([
                    'customer_say_second_image' => 'mimes:png'
                ]);
                $this->deleteFile(get_option('customer_say_second_image'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_second_image, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_third_image') && $key == 'customer_say_third_image') {
                $request->validate([
                    'customer_say_third_image' => 'mimes:png'
                ]);
                $this->deleteFile(get_option('customer_say_third_image'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_third_image, null, null);
                $option->save();
            } elseif ($request->hasFile('customer_say_fourth_image') && $key == 'customer_say_fourth_image') {
                $request->validate([
                    'customer_say_fourth_image' => 'mimes:png'
                ]);
                $this->deleteFile(get_option('customer_say_fourth_image'));
                $option->option_value = $this->saveImage('setting', $request->customer_say_fourth_image, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_first_logo') && $key == 'achievement_first_logo') {
                $request->validate([
                    'achievement_first_logo' => 'mimes:png|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_first_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_first_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_second_logo') && $key == 'achievement_second_logo') {
                $request->validate([
                    'achievement_second_logo' => 'mimes:png|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_second_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_second_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_third_logo') && $key == 'achievement_third_logo') {
                $request->validate([
                    'achievement_third_logo' => 'mimes:png|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_third_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_third_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('achievement_four_logo') && $key == 'achievement_four_logo') {
                $request->validate([
                    'achievement_four_logo' => 'mimes:png|dimensions:min_width=58,min_height=58,max_width=58,max_height=58'
                ]);
                $this->deleteFile(get_option('achievement_four_logo'));
                $option->option_value = $this->saveImage('setting', $request->achievement_four_logo, null, null);
                $option->save();
            } elseif ($request->hasFile('sign_up_left_image') && $key == 'sign_up_left_image') {
                $request->validate([
                    'sign_up_left_image' => 'mimes:png,svg'
                ]);
                $this->deleteFile(get_option('sign_up_left_image'));
                $option->option_value = $this->saveImage('setting', $request->sign_up_left_image, null, null);
                $option->save();
            } elseif ($request->hasFile('become_instructor_video_preview_image') && $key == 'become_instructor_video_preview_image') {
                $request->validate([
                    'become_instructor_video_preview_image' => 'mimes:png|dimensions:min_width=835,min_height=630,max_width=835,max_height=630'
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
            } elseif ($key == 'TIMEZONE' || $key == 'FORCE_HTTPS') {
              
                setEnvironmentValue($key, $value);

                $option->option_value = $value;
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
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        $this->showToastrMessage('success', __('Successfully Updated'));
        Artisan::call('optimize:clear');

        if(get_option('pwa_enable')){
            updateManifest();
        }

        return redirect()->back();
    }

    // public function siteShareContent()
    // {
    //     $data['title'] = 'Site Share Content Setting';
    //     $data['navApplicationSettingParentActiveClass'] = 'mm-active';
    //     $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
    //     $data['siteShareContentActiveClass'] = 'active';
    //     return view('admin.application_settings.general.site-share-content', $data);
    // }

    public function mapApiKey()
    {
        $data['title'] = 'Map Api Key Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['siteMapApiKeyActiveClass'] = 'active';
        return view('admin.application_settings.general.map-api-key', $data);
    }
   
    public function reCaptchaKey()
    {
        $data['title'] = 're-Captcha Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['siteRecaptchaKeyActiveClass'] = 'active';
        return view('admin.application_settings.general.re-captcha', $data);
    }
    
    public function googleAnalytics()
    {
        $data['title'] = 'Google Analytics Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['siteGoogleAnalyticsActiveClass'] = 'active';
        return view('admin.application_settings.general.google-analytics', $data);
    }

    public function colorSettings()
    {
        $data['title'] = 'Color Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['colorActiveClass'] = 'active';
        return view('admin.application_settings.general.color-settings', $data);
    }

    public function fontSettings()
    {
        $data['title'] = 'Font Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['fontActiveClass'] = 'active';
        return view('admin.application_settings.general.font-settings', $data);
    }

    public function BBBSettings()
    {
        $data['title'] = 'BigBlueButton Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['bbbSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.bbb-settings', $data);
    }

    public function BBBSettingsUpdate(Request $request)
    {
        $values['BBB_SECURITY_SALT'] = $request->BBB_SECURITY_SALT;
        $values['BBB_SERVER_BASE_URL'] = $request->BBB_SERVER_BASE_URL;
        $status = $request->get('bbb_status', 0);

        $option = Setting::firstOrCreate(['option_key' => 'bbb_status']);
        $option->option_value = $status;
        $option->save();

        if (!updateEnv($values)) {
            $this->showToastrMessage('error', __(SWR));
            return redirect()->back();
        }

        Artisan::call('optimize:clear');

        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function JitsiSettings()
    {
        $data['title'] = 'Jitsi Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['jitsiSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.jitsi-settings', $data);
    }

    public function JitsiSettingsUpdate(Request $request)
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
        }

        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function gMeetSettings()
    {
        $data['title'] = 'Google Meet Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['gmeetSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.gmeet_settings', $data);
    }

    public function gMeetSettingsUpdate(Request $request)
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
        }

        $this->showToastrMessage('success', 'Successfully Updated');
        return redirect()->back();
    }
   
    public function agoraSettings()
    {
        $data['title'] = 'Agora Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['agoraSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.agora_settings', $data);
    }

    public function agoraSettingsUpdate(Request $request)
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
        }

        $this->showToastrMessage('success', 'Successfully Updated');
        return redirect()->back();
    }

    public function merithubSettings()
    {
        $data['title'] = 'Merithub Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['merithubSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.merithub_settings', $data);
    }

    public function meritHubSettingsUpdate(Request $request)
    {
        $inputs = $request->validate([
            'merithub_status' => 'bail',
            'merithub_service_account_url' => 'bail|required|url',
            'merithub_class_url' => 'bail|required|url',
        ]);

        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        $this->showToastrMessage('success', 'Successfully Updated');
        return redirect()->back();
    }

    public function socialLoginSettings()
    {
        $data['title'] = 'Social Login Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['socialLoginSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.social-login-settings', $data);
    }

    public function socialLoginSettingsUpdate(Request $request)
    {
        $values['GOOGLE_LOGIN_STATUS'] = $request->GOOGLE_LOGIN_STATUS;
        $values['GOOGLE_CLIENT_ID'] = $request->GOOGLE_CLIENT_ID;
        $values['GOOGLE_CLIENT_SECRET'] = $request->GOOGLE_CLIENT_SECRET;
        $values['GOOGLE_REDIRECT_URL'] = $request->GOOGLE_REDIRECT_URL;

        $values['FACEBOOK_LOGIN_STATUS'] = $request->FACEBOOK_LOGIN_STATUS;
        $values['FACEBOOK_CLIENT_ID'] = $request->FACEBOOK_CLIENT_ID;
        $values['FACEBOOK_CLIENT_SECRET'] = $request->FACEBOOK_CLIENT_SECRET;
        $values['FACEBOOK_REDIRECT_URL'] = $request->FACEBOOK_REDIRECT_URL;

        $values['TWITTER_LOGIN_STATUS'] = $request->TWITTER_LOGIN_STATUS;
        $values['TWITTER_CLIENT_ID'] = $request->TWITTER_CLIENT_ID;
        $values['TWITTER_CLIENT_SECRET'] = $request->TWITTER_CLIENT_SECRET;
        $values['TWITTER_REDIRECT_URL'] = $request->TWITTER_REDIRECT_URL;
        if (!updateEnv($values)) {
            $this->showToastrMessage('error', __(SWR));
            return redirect()->back();
        }

        //        $envFile = app()->environmentFilePath();
        //        $str = file_get_contents($envFile);
        //        if (count($values) > 0) {
        //            foreach ($values as $envKey => $envValue) {
        //                $str .= "\n";
        //                $keyPosition = strpos($str, "{$envKey}=");
        //                $endOfLinePosition = strpos($str, "\n", $keyPosition);
        //                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        //
        //                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
        //                    $str .= "{$envKey}=\"{$envValue}\"\n";
        //                } else {
        //                    $str = str_replace($oldLine, "{$envKey}=\"{$envValue}\"", $str);
        //                }
        //            }
        //        }
        //        $str = substr($str, 0, -1);
        //        if (!file_put_contents($envFile, $str))
        //            return false;

        Artisan::call('optimize:clear');
        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function cookieSettings()
    {
        $data['title'] = 'Cookie Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['cookieSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.cookie-settings', $data);
    }

    public function cookieSettingsUpdate(Request $request)
    {
        $values['COOKIE_CONSENT_STATUS'] = $request->COOKIE_CONSENT_STATUS;
        if (!updateEnv($values)) {
            $this->showToastrMessage('error', __(SWR));
            return redirect()->back();
        }

        //        $envFile = app()->environmentFilePath();
        //        $str = file_get_contents($envFile);
        //        if (count($values) > 0) {
        //            foreach ($values as $envKey => $envValue) {
        //                $str .= "\n";
        //                $keyPosition = strpos($str, "{$envKey}=");
        //                $endOfLinePosition = strpos($str, "\n", $keyPosition);
        //                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        //
        //                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
        //                    $str .= "{$envKey}=\"{$envValue}\"\n";
        //                } else {
        //                    $str = str_replace($oldLine, "{$envKey}=\"{$envValue}\"", $str);
        //                }
        //            }
        //        }
        //        $str = substr($str, 0, -1);
        //        if (!file_put_contents($envFile, $str))
        //            return false;

        Artisan::call('optimize:clear');
        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function storageSettings()
    {
        $data['title'] = 'Storage Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['storageSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.storage-s3-settings', $data);
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
            $this->showToastrMessage('error', __(SWR));
            return redirect()->back();
        }

        //        $envFile = app()->environmentFilePath();
        //        $str = file_get_contents($envFile);
        //        if (count($values) > 0) {
        //            foreach ($values as $envKey => $envValue) {
        //                $str .= "\n";
        //                $keyPosition = strpos($str, "{$envKey}=");
        //                $endOfLinePosition = strpos($str, "\n", $keyPosition);
        //                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        //
        //                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
        //                    $str .= "{$envKey}=\"{$envValue}\"\n";
        //                } else {
        //                    $str = str_replace($oldLine, "{$envKey}=\"{$envValue}\"", $str);
        //                }
        //            }
        //        }
        //        $str = substr($str, 0, -1);
        //        if (!file_put_contents($envFile, $str))
        //            return false;

        Artisan::call('optimize:clear');
        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function vimeoSettings()
    {
        $data['title'] = 'Vimeo Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['vimeoSettingsActiveClass'] = 'active';
        return view('admin.application_settings.general.vimeo-settings', $data);
    }

    public function vimeoSettingsUpdate(Request $request)
    {
        $values['VIMEO_CLIENT'] = $request->VIMEO_CLIENT;
        $values['VIMEO_SECRET'] = $request->VIMEO_SECRET;
        $values['VIMEO_TOKEN_ACCESS'] = $request->VIMEO_TOKEN_ACCESS;
        $values['VIMEO_STATUS'] = $request->VIMEO_STATUS;
        if (!updateEnv($values)) {
            $this->showToastrMessage('error', __(SWR));
            return redirect()->back();
        }

        //        $envFile = app()->environmentFilePath();
        //        $str = file_get_contents($envFile);
        //        if (count($values) > 0) {
        //            foreach ($values as $envKey => $envValue) {
        //                $str .= "\n";
        //                $keyPosition = strpos($str, "{$envKey}=");
        //                $endOfLinePosition = strpos($str, "\n", $keyPosition);
        //                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        //
        //                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
        //                    $str .= "{$envKey}=\"{$envValue}\"\n";
        //                } else {
        //                    $str = str_replace($oldLine, "{$envKey}=\"{$envValue}\"", $str);
        //                }
        //            }
        //        }
        //        $str = substr($str, 0, -1);
        //        if (!file_put_contents($envFile, $str))
        //            return false;

        Artisan::call('optimize:clear');
        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function metaIndex()
    {
        $data['title'] = __('Meta Management');
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['metaIndexActiveClass'] = 'active';

        $data['metas'] = $this->metaModel->getOrderById('DESC', 25);
        return view('admin.application_settings.meta_manage.index', $data);
    }

    public function editMeta($uuid)
    {
        $data['title'] = __('Edit Meta');
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['metaIndexActiveClass'] = 'active';
        $data['meta'] = $this->metaModel->getRecordByUuid($uuid);
        return view('admin.application_settings.meta_manage.edit', $data);
    }

    public function updateMeta(Request $request, $uuid)
    {
        $data = [
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keyword' => $request->meta_keyword
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $this->metaModel->updateByUuid($data, $uuid);
        toastrMessage('success', 'Successfully Saved');
        return redirect()->route('settings.meta.index');
    }

    public function paymentMethod()
    {
        $data['title'] = 'Payment Method Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavPaymentOptionsSettingsActiveClass'] = 'mm-active';
        $data['paymentMethodSettingsActiveClass'] = 'active';
        return view('admin.application_settings.payment-method', $data);
    }

    public function mailConfiguration()
    {
        $data['title'] = 'Mail Configuration';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavMailConfigSettingsActiveClass'] = 'mm-active';
        $data['mailConfigSettingsActiveClass'] = 'active';
        return view('admin.application_settings.mail-configuration', $data);
    }

    public function sendTestMail(Request $request)
    {
        $data = $request;

        try {
            Mail::to($request->to)->send(new TestMail($data));
        } catch (\Exception $exception) {
            if (env('APP_DEBUG')) {
                toastrMessage('error', $exception->getMessage());
            } else {
                toastrMessage('error', 'Something is wrong. Please check your email settings');
            }
            return redirect()->back();
        }

        toastrMessage('success', 'Mail Successfully send.');
        return redirect()->back();
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


    public function instructorFeatureSetting()
    {
        $data['title'] = 'Instructor Feature';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavInstructorSettingsActiveClass'] = 'mm-active';
        $data['instructorFeatureSettingsActiveClass'] = 'active';
        $data['instructorFeatures'] = InstructorFeature::all();

        return view('admin.application_settings.instructor.instructor-feature', $data);
    }

    public function instructorFeatureSettingUpdate(Request $request)
    {
        $now = now();
        if ($request['instructor_features']) {
            if (count(@$request['instructor_features']) > 0) {
                /*
                 * Check every logo in valid
                 */
                foreach ($request['instructor_features'] as $instructor_feature) {
                    if (@$instructor_feature['title'] || @$instructor_feature['subtitle'] || @$instructor_feature['logo']) {
                        if (@$instructor_feature['id']) {
                            $feature = InstructorFeature::find($instructor_feature['id']);
                            if (@$instructor_feature['logo']) {
                                $feature->logo = $this->updateImage('instructor_feature', @$instructor_feature['logo'], $feature->logo, 'null', 'null');
                            }
                        } else {
                            $feature = new InstructorFeature();
                            if (@$instructor_feature['logo']) {
                                $feature->logo = $this->saveImage('instructor_feature', @$instructor_feature['logo'], 'null', 'null');
                            }
                        }
                        $feature->title = @$instructor_feature['title'];
                        $feature->subtitle = @$instructor_feature['subtitle'];
                        $feature->updated_at = $now;
                        $feature->save();
                    }
                }
            }
        }

        InstructorFeature::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $this->deleteFile($q->logo);
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function instructorProcedureSetting()
    {
        $data['title'] = 'Instructor Procedure';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavInstructorSettingsActiveClass'] = 'mm-active';
        $data['instructorProcedureSettingsActiveClass'] = 'active';
        $data['instructorProcedures'] = InstructorProcedure::all();

        return view('admin.application_settings.instructor.instructor-procedure', $data);
    }

    public function instructorProcedureSettingUpdate(Request $request)
    {
        $now = now();
        if ($request['instructor_procedures']) {
            if (count(@$request['instructor_procedures']) > 0) {
                foreach ($request['instructor_procedures'] as $instructor_procedure) {
                    if (@$instructor_procedure['title'] || @$instructor_procedure['subtitle'] || @$instructor_procedure['image']) {
                        if (@$instructor_procedure['id']) {
                            $procedure = InstructorProcedure::find($instructor_procedure['id']);
                            if (@$instructor_procedure['image']) {
                                $procedure->image = $this->updateImage('instructor_procedure', @$instructor_procedure['image'], $procedure->image, 'null', 'null');
                            }
                        } else {
                            $procedure = new InstructorProcedure();
                            if (@$instructor_procedure['image']) {
                                $procedure->image = $this->saveImage('instructor_procedure', @$instructor_procedure['image'], 'null', 'null');
                            }
                        }
                        $procedure->title = @$instructor_procedure['title'];
                        $procedure->subtitle = @$instructor_procedure['subtitle'];
                        $procedure->updated_at = $now;
                        $procedure->save();
                    }
                }
            }
        }

        InstructorProcedure::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $this->deleteFile($q->image);
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function instructorCMSSetting()
    {
        $data['title'] = 'Instructor CMS';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavInstructorSettingsActiveClass'] = 'mm-active';
        $data['instructorCMSSettingsActiveClass'] = 'active';

        return view('admin.application_settings.instructor.cms', $data);
    }

    public function faqCMS()
    {
        if (!Auth::user()->can('content_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'FAQ CMS';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavFAQSettingsActiveClass'] = 'mm-active';
        $data['faqCMSSettingsActiveClass'] = 'active';

        return view('admin.application_settings.faq.cms', $data);
    }

    public function faqTab()
    {
        if (!Auth::user()->can('content_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'FAQ Tab';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavFAQSettingsActiveClass'] = 'mm-active';
        $data['faqCMSTabActiveClass'] = 'active';

        return view('admin.application_settings.faq.tab-service', $data);
    }

    public function faqQuestion()
    {
        if (!Auth::user()->can('content_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'FAQ Question & Answer';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavFAQSettingsActiveClass'] = 'mm-active';
        $data['faqQuestionActiveClass'] = 'active';
        $data['faqQuestions'] = FaqQuestion::all();

        return view('admin.application_settings.faq.question', $data);
    }

    public function faqQuestionUpdate(Request $request)
    {
        if (!Auth::user()->can('content_setting')) {
            abort('403');
        } // end permission checking

        $now = now();
        if ($request['question_answers']) {
            if (count(@$request['question_answers']) > 0) {
                foreach ($request['question_answers'] as $question_answers) {
                    if (@$question_answers['question']) {
                        if (@$question_answers['id']) {
                            $question_answer = FaqQuestion::find($question_answers['id']);
                        } else {
                            $question_answer = new FaqQuestion();
                        }
                        $question_answer->question = @$question_answers['question'];
                        $question_answer->answer = @$question_answers['answer'];
                        $question_answer->updated_at = $now;
                        $question_answer->save();
                    }
                }
            }
        }

        FaqQuestion::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function supportTicketCMS()
    {
        if (!Auth::user()->can('content_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Support Ticket CMS';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavSupportSettingsActiveClass'] = 'mm-active';
        $data['supportCMSSettingsActiveClass'] = 'active';

        return view('admin.application_settings.support_ticket.cms', $data);
    }

    public function supportTicketQuesAns()
    {
        $data['title'] = 'Support Ticket Question & Answer';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavSupportSettingsActiveClass'] = 'mm-active';
        $data['supportQuestionActiveClass'] = 'active';
        $data['supportTickets'] = SupportTicketQuestion::all();

        return view('admin.application_settings.support_ticket.question', $data);
    }

    public function supportTicketQuesAnsUpdate(Request $request)
    {
        $now = now();
        if ($request['question_answers']) {
            if (count(@$request['question_answers']) > 0) {
                foreach ($request['question_answers'] as $question_answers) {
                    if (@$question_answers['question']) {
                        if (@$question_answers['id']) {
                            $question_answer = SupportTicketQuestion::find($question_answers['id']);
                        } else {
                            $question_answer = new SupportTicketQuestion();
                        }
                        $question_answer->question = @$question_answers['question'];
                        $question_answer->answer = @$question_answers['answer'];
                        $question_answer->updated_at = $now;
                        $question_answer->save();
                    }
                }
            }
        }

        SupportTicketQuestion::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function maintenanceMode()
    {
        $data['title'] = 'Maintenance Mode Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavMaintenanceModeActiveClass'] = 'mm-active';
        $data['maintenanceModeActiveClass'] = 'active';

        return view('admin.application_settings.maintenance-mode', $data);
    }
   
    public function comingSoonMode()
    {
        $data['title'] = 'Coming Soon Mode Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavComingSoonModeSettingsActiveClass'] = 'mm-active';
        $data['comingSoonModeActiveClass'] = 'active';

        return view('admin.application_settings.coming-soon-mode', $data);
    }

    public function deviceControl()
    {
        $data['title'] = 'Device Control Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['deviceControlActiveClass'] = 'active';

        return view('admin.application_settings.device_control', $data);
    }

    public function privateMode()
    {
        $data['title'] = 'Private Mode Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['privateModeActiveClass'] = 'active';

        return view('admin.application_settings.private_mode', $data);
    }

    public function subscriptionMode()
    {
        $data['title'] = 'Subscription Mode Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['subscriptionModeActiveClass'] = 'active';

        return view('admin.application_settings.subscription', $data);
    }

    public function saasMode()
    {
        $data['title'] = 'SaaS Mode Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['saasModeActiveClass'] = 'active';
        $data['saas_ins'] = Package::where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR)->get();
        $data['saas_org'] = Package::where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION)->get();

        return view('admin.application_settings.saas', $data);
    }
    
    public function rewardPoints()
    {
        $data['title'] = 'Reward Points Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['rewardPointActiveClass'] = 'active';
        
        return view('admin.application_settings.reward_points', $data);
    }
    public function registrationBonus()
    {
        $data['title'] = 'Registration Bonus Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['registrationSystemActiveClass'] = 'active';
        
        return view('admin.application_settings.registration_system', $data);
    }
    public function refundSystem()
    {
        $data['title'] = 'Refund System Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['refundSystemActiveClass'] = 'active';
        
        return view('admin.application_settings.refund_system', $data);
    }
    public function cashbackSettings()
    {
        $data['title'] = 'Cashback Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['cashbackSettingActiveClass'] = 'active';
        
        return view('admin.application_settings.cashbook_setting', $data);
    }
    
    public function chatSystem()
    {
        $data['title'] = 'Chat Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['chatSettingActiveClass'] = 'active';
        
        return view('admin.application_settings.chat_setting', $data);
    }
   
    public function courseGiftSystem()
    {
        $data['title'] = 'Course Gift System';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['courseGiftSettingActiveClass'] = 'active';
        
        return view('admin.application_settings.course_gift_setting', $data);
    }
   
    public function walletCheckoutSystem()
    {
        $data['title'] = 'Wallet Checkout System';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['walletCheckoutEnableSettingActiveClass'] = 'active';
        
        return view('admin.application_settings.wallet_checkout_enable', $data);
    }
   
    public function walletRechargeSystem()
    {
        $data['title'] = 'Wallet Recharge System';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['walletRechargeSettingActiveClass'] = 'active';
        
        return view('admin.application_settings.wallet_recharge_system', $data);
    }

    public function maintenanceModeChange(Request $request)
    {
        if ($request->maintenance_mode == 1) {
            $request->validate(
                [
                    'maintenance_mode' => 'required',
                    'maintenance_secret_key' => 'required|min:6'
                ],
                [
                    'maintenance_secret_key.required' => 'The maintenance mode secret key is required.',
                ]
            );
        } else {
            $request->validate([
                'maintenance_mode' => 'required',
            ]);
        }

        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        if ($request->maintenance_mode == 1) {
            Artisan::call('up');
            $secret_key = 'down --secret="' . $request->maintenance_secret_key . '"';
            Artisan::call($secret_key);
        } else {
            $option = Setting::firstOrCreate(['option_key' => 'maintenance_secret_key']);
            $option->option_value = null;
            $option->save();
            Artisan::call('up');
        }

        $this->showToastrMessage('success', __('Maintenance Mode has been changed'));
        return redirect()->back();
    }

    public function comingSoonModeChange(Request $request)
    {
        if ($request->coming_soon_mode == 1) {
            $request->validate(
                [
                    'coming_soon_mode' => 'required',
                    'coming_soon_secret_key' => 'required|min:6',
                    'coming_live_at' => 'required|date',
                    'coming_soon_title' => 'required',
                    'coming_soon_description' => 'required',
                ],
                [
                    'coming_soon_secret_key.required' => 'The coming soon mode secret key is required.',
                ]
            );
        } else {
            $request->validate([
                'coming_soon_mode' => 'required',
            ]);
        }

        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        try{
            foreach ($inputs as $key => $value) {
                $option = Setting::firstOrCreate(['option_key' => $key]);
                $option->option_value = $value;
                $option->save();
            }
        }finally{
            if ($request->coming_soon_mode == 1) {
                Artisan::call('up');
                Artisan::call('optimize:clear');
                Artisan::call('config:cache');
                $secret_key = 'down --secret="' . $request->coming_soon_secret_key . '" --render=/zainiklab.coming-soon';
                Artisan::call($secret_key);
            } else {
                $option = Setting::firstOrCreate(['option_key' => 'coming_soon_secret_key']);
                $option->option_value = null;
                $option->save();
                $option = Setting::firstOrCreate(['option_key' => 'coming_live_at']);
                $option->option_value = null;
                $option->save();
                $option = Setting::firstOrCreate(['option_key' => 'coming_soon_title']);
                $option->option_value = null;
                $option->save();
                $option = Setting::firstOrCreate(['option_key' => 'coming_soon_description']);
                $option->option_value = null;
                $option->save();
                Artisan::call('up');
            }
    
            $this->showToastrMessage('success', __('Coming Soon Mode has been changed'));
            return redirect()->back();
        }

    }

    public function deviceControlChange(Request $request)
    {
        $request->validate([
            'device_limit' => 'required|integer|min:1',
        ]);

        $option = Setting::firstOrCreate(['option_key' => 'device_limit']);
        $option->option_value = $request->device_limit;
        $option->save();

        $option = Setting::firstOrCreate(['option_key' => 'device_control']);
        $option->option_value = $request->device_control;
        $option->save();

        $this->showToastrMessage('success', 'Device Control has been changed');
        return redirect()->back();
    }

    public function privateModeChange(Request $request)
    {
        $option = Setting::firstOrCreate(['option_key' => 'private_mode']);
        $option->option_value = $request->private_mode;
        $option->save();

        $this->showToastrMessage('success', 'Private mode has been changed');
        return redirect()->back();
    }

    public function subscriptionModeChange(Request $request)
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
        }
        
        $this->showToastrMessage('success', 'Subscription mode has been changed');
        return redirect()->back();
    }

    public function saasModeChange(Request $request)
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
        }

        $this->showToastrMessage('success', 'SaaS mode has been changed');
        return redirect()->back();
    }

    public function cacheSettings()
    {
        $data['title'] = 'Cache Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavCacheActiveClass'] = 'mm-active';
        $data['cacheActiveClass'] = 'active';

        return view('admin.application_settings.cache-settings', $data);
    }

    public function referralSettings()
    {
        $data['title'] = 'Affiliation settings';
        $data['subNavReferralActiveClass'] = 'mm-active';
        $data['referralActiveClass'] = 'active';
        return view('admin.affiliate.affiliation-settings', $data);
    }

    public function referralSettingsUpdate(Request $request)
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
        }

        $this->showToastrMessage('success', __('Successfully Updated'));
        return redirect()->back();
    }

    public function cacheUpdate($id)
    {
        if ($id == 1) {
            Artisan::call('view:clear');
            $this->showToastrMessage('success', __('Views cache cleared successfully.'));
            return redirect()->back();
        } elseif ($id == 2) {
            Artisan::call('route:clear');
            $this->showToastrMessage('success', __('Route cache cleared successfully.'));
            return redirect()->back();
        } elseif ($id == 3) {
            Artisan::call('config:clear');
            $this->showToastrMessage('success', __('Configuration cache cleared successfully.'));
            return redirect()->back();
        } elseif ($id == 4) {
            Artisan::call('cache:clear');
            $this->showToastrMessage('success', __('Application cache cleared successfully.'));
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function migrateSettings()
    {
        $data['title'] = 'Migrate Settings';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavMigrateActiveClass'] = 'mm-active';
        $data['migrateActiveClass'] = 'active';

        return view('admin.application_settings.migrate-settings', $data);
    }

    public function migrateUpdate()
    {
        Artisan::call('migrate');
        $this->showToastrMessage('success', __('Migrated successfully.'));
        return redirect()->back();
    }
   
    public function generateSiteMap()
    {
        set_time_limit(1200);
        SitemapGenerator::create(url(''))->writeToFile(public_path('uploads/sitemap.xml'));
        $filepath = public_path('uploads/sitemap.xml');
        return Response::download($filepath); 
    }
}
