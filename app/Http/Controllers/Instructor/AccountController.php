<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CardRequest;
use App\Models\User_card;
use App\Models\User_paypal;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use  ImageSaveTrait, General;

    public function myCard()
    {
        $data['title'] = 'My Card';
        $data['navPaymentActiveClass'] = 'active';
        return view('instructor.account.my-card', $data);
    }

    public function saveMyCard(CardRequest $request)
    {
        User_card::updateOrCreate([
            'user_id' => Auth::id()
        ],[
            'user_id' => Auth::id(),
            'card_number' => $request->card_number,
            'card_holder_name' => $request->card_holder_name,
            'month' => $request->month,
            'year' => $request->year,
        ]);

        $this->showToastrMessage('success', __('Update Successfully'));
        return redirect()->back();
    }

    public function savePaypal(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        User_paypal::updateOrCreate([
            'user_id' => Auth::id()
        ],[
            'user_id' => Auth::id(),
            'email' => $request->email
        ]);

        $this->showToastrMessage('success', __('Update Successfully'));
        return redirect()->back();
    }
}
