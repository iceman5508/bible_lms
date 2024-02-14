<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Enrollment;
use App\Models\Order_item;
use App\Models\Package;
use App\Models\RankingLevel;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class U2V3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u2v3 {--lqs=} {--v=}';

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
            
            if ($dbBuildVersion < 3 && $dbBuildVersion > 1) {
                
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
                    //Delete all ranking levels
                    DB::table('ranking_levels')->where('id', '!=', 0)->delete();
               
                    DB::unprepared($lqs);
                }

                //migrate all course user to course_instructor
                $allCourses = Course::all();
                foreach ($allCourses as $course) {
                    CourseInstructor::firstOrCreate(
                        [
                            'course_id' => $course->id,
                            'instructor_id' => $course->user_id
                        ],
                        [
                            'course_id' => $course->id,
                            'instructor_id' => $course->user_id,
                            'share' => 100,
                            'status' => 1
                        ]
                    );
                }

                //END migrate all course user to course_instructor

                //migrate all order items to enrollments
                $orderItems = Order_item::join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->where('payment_status', 'paid')
                    ->select('order_items.*')
                    ->groupBy('order_items.id')
                    ->get();

                foreach ($orderItems as $item) {
                    Enrollment::firstOrCreate(
                        [
                            'order_id' => $item->order_id,
                            'user_id' => $item->user_id,
                            'owner_user_id' => $item->owner_user_id,
                            'course_id' => $item->course_id,
                            'consultation_slot_id' => $item->consultation_slot_id,
                            'bundle_id' => $item->bundle_id,
                            'start_date' => $item->created_at,
                            'end_date' => MAX_EXPIRED_DATE,
                        ]
                    );
                }
                //END migrate all order items to enrollments

                //migrate all instructors to assign default 
                $users = User::where('role', USER_ROLE_INSTRUCTOR)
                    ->join('instructors as ins', 'ins.user_id', '=', 'users.id')
                    ->where('ins.status', STATUS_APPROVED)
                    ->select('users.id')
                    ->get();

                foreach ($users as $user) {
                    if (!UserPackage::where('user_id', $user->id)->first()) {
                        //set default package
                        $package = Package::where('is_default', 1)->where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR)->firstOrFail();
                        $userPackageData['user_id'] = $user->id;
                        $userPackageData['is_default'] = 1;
                        $userPackageData['package_id'] = $package->id;
                        $userPackageData['subscription_type'] = get_option('subscription_default_package_type', 'monthly') == 'yearly' ?  SUBSCRIPTION_TYPE_YEARLY : SUBSCRIPTION_TYPE_MONTHLY;
                        $userPackageData['student'] = $package->student;
                        $userPackageData['instructor'] = $package->instructor;
                        $userPackageData['course'] = $package->course;
                        $userPackageData['consultancy'] = $package->consultancy;
                        $userPackageData['subscription_course'] = $package->subscription_course;
                        $userPackageData['bundle_course'] = $package->bundle_course;
                        $userPackageData['product'] = $package->product;
                        $userPackageData['admin_commission'] = $package->admin_commission;
                        $userPackageData['payment_id'] = NULL;
                        $userPackageData['enroll_date'] = now();
                        $userPackageData['expired_date'] = get_option('subscription_default_package_type', 'monthly') == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();
                        UserPackage::create($userPackageData);
                    }

                    setBadge($user->id);
                }

                //END migrate all instructors to assign default 

                setCustomerBuildVersion(3);
                setCustomerCurrentVersion();
            }

            Log::info('from u2v3');
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
