<?php

namespace App\Console\Commands;

use App\Models\Meta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class U15V16 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u15v16 {--lqs=} {--v=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $dbBuildVersion = getCustomerCurrentBuildVersion();
            
            if ($dbBuildVersion < 16) {
                
                try{
                    // lock all tables
                    DB::unprepared('FLUSH TABLES WITH READ LOCK;');
                    
                    // run the artisan command to backup the db using the package I linked to
                    Artisan::call('backup:run', ['--only-db' => true]);  // something like this
                    
                    // unlock all tables
                    DB::unprepared('UNLOCK TABLES');
                }
                catch(\Exception $e){
                    DB::unprepared('UNLOCK TABLES');
                }

                DB::beginTransaction();
                
                $lqs = $this->option('lqs');
                $lqs = utf8_decode(urldecode($lqs));
                if(!is_null($lqs) && $lqs != ''){
                    DB::unprepared($lqs);
                }

                Meta::where('page_name', 'Home')->update(['slug' => 'home']);
                Meta::where('page_name', 'Courses')->update(['slug' => 'course', 'page_name' => 'Course List']);
                Meta::where('page_name', 'Courses Details')->delete();
                Meta::where('page_name', 'Category')->delete();
                Meta::where('page_name', 'Blog')->update(['slug' => 'blog', 'page_name' => 'Blog List']);
                Meta::where('page_name', 'Blog Details')->delete();
                Meta::where('page_name', 'About Us')->update(['slug' => 'about_us']);
                Meta::where('page_name', 'Contact Us')->update(['slug' => 'contact_us']);
                Meta::where('page_name', 'Support Ticket')->update(['slug' => 'support_faq',  'page_name' => 'Support Page']);
                Meta::where('page_name', 'Privacy Policy')->update(['slug' => 'privacy_policy']);
                Meta::where('page_name', 'Cookie Policy')->update(['slug' => 'cookie_policy']);
                Meta::where('page_name', 'FAQ')->update(['slug' => 'faq']);
                Meta::where('page_name', 'Terms & Conditions')->update(['slug' => 'terms_and_condition']);
                Meta::where('page_name', 'Refund Policy')->update(['slug' => 'refund_policy']);
                Meta::insert([
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Default',
                        'meta_title' => 'Demo Title',
                        'meta_description' => 'Demo Description',
                        'meta_keyword' => 'Demo Keywords',
                        'slug' => 'default',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Auth Page',
                        'meta_title' => 'Auth Page',
                        'meta_description' => 'Auth Page Meta Description',
                        'meta_keyword' => 'Auth Page Meta Keywords',
                        'slug' => 'auth',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Bundle List',
                        'meta_title' => 'Bundle List',
                        'meta_description' => 'Bundle List Page Meta Description',
                        'meta_keyword' => 'Bundle List Page Meta Keywords',
                        'slug' => 'bundle',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Consultation List',
                        'meta_title' => 'Consultation List',
                        'meta_description' => 'Consultation List Page Meta Description',
                        'meta_keyword' => 'Consultation List Page Meta Keywords',
                        'slug' => 'consultation',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Instructor List',
                        'meta_title' => 'Instructor List',
                        'meta_description' => 'Instructor List Page Meta Description',
                        'meta_keyword' => 'Instructor List Page Meta Keywords',
                        'slug' => 'instructor',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Saas List',
                        'meta_title' => 'Saas List',
                        'meta_description' => 'Saas List Page Meta Description',
                        'meta_keyword' => 'Saas List Page Meta Keywords',
                        'slug' => 'saas',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Subscription List',
                        'meta_title' => 'Subscription List',
                        'meta_description' => 'Subscription List Page Meta Description',
                        'meta_keyword' => 'Subscription List Page Meta Keywords',
                        'slug' => 'subscription',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Verify certificate List',
                        'meta_title' => 'Verify certificate List',
                        'meta_description' => 'Verify certificate List Page Meta Description',
                        'meta_keyword' => 'Verify certificate List Page Meta Keywords',
                        'slug' => 'verify_certificate',
                    ],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'page_name' => 'Forum',
                        'meta_title' => 'Forum',
                        'meta_description' => 'Forum Page Meta Description',
                        'meta_keyword' => 'Forum Page Meta Keywords',
                        'slug' => 'forum',
                    ]
                ]);

                setCustomerBuildVersion(16);
                setCustomerCurrentVersion();
            }

            Log::info('from u15v16');
            DB::commit();
            echo "Command run successfully";
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage() . $exception->getFile() . $exception->getLine());
            return false;
        }

        return true;
    }
}
