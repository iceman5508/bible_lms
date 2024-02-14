<?php


namespace App\Traits;


use App\Mail\UserEnailVerificaion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

trait EmailSendTrait
{
    public function userEmailVerification(){
        $user = Auth::user();

        try {
            Mail::to($user->email)->send(new UserEnailVerificaion($user));
        }catch (\Exception $exception){
            toastrMessage('error', 'Something is wrong. Please contact with '. get_option('app_name') .' support team');
            return redirect()->back();
        }
    }
}
