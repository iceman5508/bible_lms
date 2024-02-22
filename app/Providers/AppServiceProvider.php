<?php

namespace App\Providers;

use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use DB;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        if(config('app.force_https')) {
            \URL::forceScheme('https');
        }

        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);

        /**
         * Create Some Buttons
         */
        Blade::include('layouts.element.form.save-with-another', 'saveWithAnotherButton');
        Blade::include('layouts.element.form.update-button', 'updateButton');


        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role == 1;
        });

        Blade::if('instructor', function () {
            return auth()->check() && auth()->user()->role == 2;
        });

        Blade::if('student', function () {
            return auth()->check() && auth()->user()->role == 3;
        });
        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $allOptions = [];
                $allOptions['settings'] = Setting::all()->pluck('option_value', 'option_key')->toArray();
                if(env('IS_LOCAL', 0) && (request()->input('theme', NULL) || request()->cookie('theme', NULL))){
                    if(request()->input('theme', NULL)){
                        $themeValue = request()->input('theme');
                        cookie()->queue('theme', $themeValue, 120);
                        $allOptions['settings']['theme'] = $themeValue;
                    }elseif(request()->cookie('theme', NULL)){
                        $allOptions['settings']['theme'] = request()->cookie('theme', NULL);
                    }
                }
                config($allOptions);
                config(['broadcasting.default' => get_option('broadcast_default', 'null')]);
                config(['broadcasting.connections.pusher.key' => get_option('pusher_app_key', 'null')]);
                config(['broadcasting.connections.pusher.secret' => get_option('pusher_app_secret', 'null')]);
                config(['broadcasting.connections.pusher.app_id' => get_option('pusher_app_id', 'null')]);
                config(['broadcasting.connections.pusher.options.cluster' => get_option('pusher_app_cluster', 'null')]);

                $themePath = getThemePath();

                View::composer($themePath . '.layouts.navbar', function ($view) {
                    $data['categories'] = Category::with('subcategories')->active()->get();
                    $data['cartQuantity'] = CartManagement::whereUserId(@Auth::user()->id)->count();
                    $data['wishlistQuantity'] = Wishlist::whereUserId(@Auth::user()->id)->count();
                    $data['menus'] = Menu::all();
                    $data['staticMenus'] = Menu::active()->whereType(1)->get();
                    $data['dynamicMenus'] = Menu::active()->whereType(2)->get();

                    $data['student_notifications'] = Notification::where('user_id', @auth()->user()->id)->where('user_type', 3)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
                    $data['instructor_notifications'] = Notification::where('user_id', @auth()->user()->id)->where('user_type', 2)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
                    $view->with($data);
                });

                View::composer($themePath . '.layouts.footer', function ($view) {
                    $data['menus'] = Menu::active()->get();
                    $data['dynamicMenus'] = Menu::active()->whereType(2)->get();
                    $data['footerLeftMenus'] = Menu::active()->whereType(3)->get();
                    $data['footerRightMenus'] = Menu::active()->whereType(4)->get();

                    $view->with($data);
                });

                View::composer('admin.common.header', function ($view) {
                    $data['adminNotifications'] = Notification::where('user_type', 1)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
                    $count = 0;
                    foreach ($data['adminNotifications'] as $notification) {
                        if ($notification->sender) {
                            $count++;
                        }
                    }
                    $data['totalAdminNotifications'] = $count;
                    $view->with($data);
                });
            }

            $language = Language::where('default_language', 'on')->first();
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            } else {
                $language = Language::find(1);
                if ($language) {
                    $ln = $language->iso_code;
                    session(['local' => $ln]);
                    App::setLocale(session()->get('local'));
                }
            }
        } catch (\Exception $e) {
            //
        }
    }
}
