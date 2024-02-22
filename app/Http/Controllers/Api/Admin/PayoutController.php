<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MonthlyDistributionHistory;
use App\Models\SubscriptionCommissionHistory;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Withdraw;
use App\Traits\ApiStatusTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PayoutController extends Controller
{
    use ApiStatusTrait, SendNotification;

    public function newWithdraw()
    {
        if (!Auth::user()->hasPermissionTo('payout', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['withdraws'] = Withdraw::pending()->orderBy('id', 'DESC')->with(['beneficiary', 'user.instructor'])->paginate(20);
        return $this->success($data);
    }

    public function completeWithdraw()
    {
        if (!Auth::user()->hasPermissionTo('payout', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['withdraws'] = Withdraw::completed()->orderBy('id', 'DESC')->with(['beneficiary', 'user.instructor'])->paginate(20);
        return $this->success($data);
    }

    public function rejectedWithdraw()
    {
        if (!Auth::user()->hasPermissionTo('payout', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['withdraws'] = Withdraw::rejected()->orderBy('id', 'DESC')->with(['beneficiary', 'user.instructor'])->paginate(20);
        return $this->success($data);
    }
}
