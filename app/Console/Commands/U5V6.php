<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class U5V6 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u5v6 {--lqs=} {--v=}';

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
            
            if ($dbBuildVersion < 6) {
                
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

                rename(resource_path('/lang/sa'), resource_path('/lang/ar'));
                rename(resource_path('/lang/sa.json'), resource_path('/lang/ar.json'));

                setCustomerBuildVersion(6);
                setCustomerCurrentVersion();
            }

            Log::info('from u5v6');
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
