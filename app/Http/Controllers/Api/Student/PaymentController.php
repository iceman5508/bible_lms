<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ProfileRequest;
use App\Models\Bank;
use App\Models\City;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\InstructorFeature;
use App\Models\InstructorProcedure;
use App\Models\Organization;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
{

    use  ImageSaveTrait, General, SendNotification, ApiStatusTrait;

    protected $studentModel;
    protected $organizationModel;
    protected $instructorModel;

    public function __construct(Student $student, Instructor $instructor, Organization $organization)
    {
        $this->studentModel = new Crud($student);
        $this->instructorModel = new Crud($instructor);
        $this->organizationModel = new Crud($organization);
    }

    public function gatewayList()
    {
        $gateways = getPaymentMethodNameForApi();
        $data = [];

        foreach($gateways as $index => $gateway){
            if(($index == INSTAMOJO && get_option('im_status') == 1) || ($index == MERCADOPAGO && get_option('mercado_status') == 1) || get_option($index.'_status') == 1){
                if($index == INSTAMOJO){
                    $currency = get_option('im_currency');
                    $conversion_rate = get_option('im_conversion_rate');
                }else if($index == MERCADOPAGO){
                    $currency = get_option('mercado_currency');
                    $conversion_rate = get_option('mercado_conversion_rate');
                }else{
                    $currency = get_option($index.'_currency');
                    $conversion_rate = get_option($index.'_conversion_rate');
                }

                $data[] = [
                    'slug' => $index,
                    'name' => $gateway,
                    'currency' => $currency,
                    'conversion_rate' => $conversion_rate,
                ];
                
            }
        }

        return $this->success($data);
    }
   
    public function getActiveBank()
    {
        $data = Bank::all();
        return $this->success($data);
    }
}
