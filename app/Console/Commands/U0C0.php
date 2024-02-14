<?php

namespace App\Console\Commands;

use App\Http\Controllers\Logger;
use Illuminate\Console\Command;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class U0C0 extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u0c0';
    
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
    
    // protected $logger;
     
    // public function __construct()
    // {
    //     $this->logger = new Logger('update.log');
    // }

    public function handle()
    {
        $path = storage_path('app/Source-code.zip');
        $demoPath = storage_path('app/updates');
        
        $response['success'] = 0;
        $response['message'] = 'File Not exist on storage';

        $this->logger->log('Update Start', '==========');
        if(file_exists($path)){
            $this->logger->log('File Found', 'Success');
            $zip = new ZipArchive;

            if(is_dir($demoPath)){
                $this->logger->log('Updates directory', 'exist');
                $this->logger->log('Updates directory', 'deleting');
                File::deleteDirectory($demoPath);
                $this->logger->log('Updates directory', 'deleted');
            }

            $this->logger->log('Updates directory', 'creating');
            File::makeDirectory($demoPath, 0777, true, true);
            $this->logger->log('Updates directory', 'created');
            
            $this->logger->log('Zip', 'opening');
            $res = $zip->open($path);
            
            if ($res === true) {
                $this->logger->log('Zip', 'Open successfully');
                try{
                    $this->logger->log('Zip Extracting', 'Start');
                    $res = $zip->extractTo($demoPath);
                    $this->logger->log('Zip Extracting', 'END');
                    $this->logger->log('Get update note', 'START');
                    $versionFile = file_get_contents($demoPath.DIRECTORY_SEPARATOR.'update_note.txt');
                    $this->logger->log('Get update note', 'END');
                    $this->logger->log('Get Build Version from update note', 'START');
                    $codeVersion = json_decode($versionFile)->build_version;
                    $this->logger->log('Get Build Version from update note', 'END');
                    $this->logger->log('Get current version', 'START');
                    $currentVersion = getCustomerCurrentBuildVersion();
                    $this->logger->log('Get current version', 'END');
                    $this->logger->log('Checking if updatable version from api', 'START');
                    $apiResponse = Http::acceptJson()->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                        'app' => config('app.app_code'),
                        'is_localhost' => env('IS_LOCAL', false),
                    ]);
                    $this->logger->log('Checking if updatable version from api', 'END');
                    
                    if($apiResponse->successful()){
                        $this->logger->log('Response', 'Success');
                        $data = $apiResponse->object();
                        $this->logger->log('Response Data', json_encode($data));
                        $latestVersion = $data->data->bv;
                        if($data->status === 'success'){
                            $this->logger->log('Response status', 'Success');
                            $this->logger->log('Checking if updatable code', 'START');
                            if($latestVersion == $codeVersion && $codeVersion > $currentVersion){
                                $this->logger->log('Checking if updatable code', 'True');
                                $this->logger->log('Move file', 'START');

                                File::move($demoPath.DIRECTORY_SEPARATOR.'composer.json', base_path('composer.json'));
                                File::move($demoPath.DIRECTORY_SEPARATOR.'package.json', base_path('package.json'));
                                File::move($demoPath.DIRECTORY_SEPARATOR.'README.md', base_path('README.md'));
                                File::move($demoPath.DIRECTORY_SEPARATOR.'.gitignore', base_path('.gitignore'));
                                File::move($demoPath.DIRECTORY_SEPARATOR.'.env.example', base_path('.env.example'));
                                File::move($demoPath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'lms.sql', base_path('storage/app/lms.sql'));
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'bootstrap', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'database', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'routes', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'css', base_path('resources'), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'js', base_path('resources'), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'sass', base_path('resources'), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views', base_path('resources'), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'public', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'vendor', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'config', base_path(), true);
                                File::moveDirectory($demoPath.DIRECTORY_SEPARATOR.'app', base_path(), true);
                                
                                $response['success'] = 1;
                                $response['message'] = 'Successfully done';
                                $this->logger->log('Move file', 'Done');
                                $this->logger->log('', '===============Update END==============');
                            }
                            else{
                                $response['message'] = 'Your code is not up to date';
                                $this->logger->log('Version', 'Not matched');
                                $this->logger->log('', '===============Update END==============');
                            }
                        }
                        else{
                            $response['message'] = $data->message;
                            $this->logger->log('Response Status', 'Failed');
                            $this->logger->log('', '===============Update END==============');
                        }
                    }
                    else{
                        $data = $apiResponse->object();
                        $response['message'] = $data['message'];
                        $this->logger->log('Response', 'Failed');
                        $this->logger->log('', '===============Update END==============');
                    }

                    File::deleteDirectory($demoPath);
                }
                catch(\Exception $e){
                    $response['message'] = $e->getMessage();
                    $this->logger->log('Exception', $e->getMessage());
                    $this->logger->log('', '===============Update END==============');
                }
                $zip->close();
            }

            $this->logger->log('Zip', 'Open failed');
            $this->logger->log('', '===============Update END==============');
        }
        return $response;
    }
}
