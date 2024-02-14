<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficiary;
use App\Models\Transaction;
use App\Models\User_paypal;
use App\Models\WalletRecharge;
use App\Models\Withdraw;
use App\Traits\General;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    use General, SendNotification;
    public function index()
    {
        if (Auth::user()->role == USER_ROLE_STUDENT || Auth::user()->is_affiliator == AFFILIATOR || (Auth::user()->role == USER_ROLE_INSTRUCTOR && @Auth::user()->instructor->status == 1) || (Auth::user()->role == USER_ROLE_ORGANIZATION && @Auth::user()->organization->status == 1)) {
            $data['pageTitle'] = 'Wallet';
            $data['myBalance'] = int_to_decimal(Auth::user()->balance);
            $data['beneficiaries'] = Beneficiary::where(['user_id' => Auth::id()])->whereStatus(STATUS_APPROVED)->get();
            return view('frontend.wallet.my-wallet', $data);
        }
        $this->showToastrMessage('warning', __('You are not an affiliator'));
        return redirect()->back();
    }
    public function transactionHistory(Request $request)
    {
        if ($request->ajax()) {
            $aff = Transaction::where(['user_id' => Auth::id()])->orderBy('transactions.id', 'DESC');
            return datatables($aff)
                ->addColumn('type', function ($item) {
                    return transactionTypeText($item->type);
                })->addColumn('amount', function ($item) {
                    if (get_currency_placement() == 'after') {
                        return $item->amount . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->amount;
                    }
                })->addColumn('date', function ($item) {
                    return $item->created_at->format('Y-m-d H:i:s');
                })->make(true);
        }
    }
    public function WithdrawalHistory(Request $request)
    {
        if ($request->ajax()) {
            $aff = Withdraw::where(['user_id' => Auth::id()])->orderBy('withdraws.id', 'DESC')->with('beneficiary');
            return datatables($aff)
                ->addColumn('amount', function ($item) {
                    if (get_currency_placement() == 'after') {
                        return $item->amount . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->amount;
                    }
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == WITHDRAWAL_STATUS_PENDING) {
                        return '<span class="text-info">' . statusWithdrawalStatus($item->status) . '</span>';
                    } elseif ($item->status == WITHDRAWAL_STATUS_REJECTED) {
                        return '<span class="text-danger">' . statusWithdrawalStatus($item->status) . '</span>';
                    } else {
                        return '<span class="color-green">' . statusWithdrawalStatus($item->status) . '</span>' .
                            '<a target="_blank" href="' . route('wallet.download-receipt', [$item->uuid]) . '">' .
                            '<span class="iconify" data-icon="bxs:file-pdf"></span>' .
                            '</a>';
                    }
                })
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('Y-m-d H:i:s');
                })->addColumn('beneficiary', function ($item) {
                    return getBeneficiaryDetails($item->beneficiary);
                })
                ->rawColumns(['status', 'beneficiary'])
                ->make(true);
        }
    }

    public function rechargeHistory(Request $request)
    {
        if ($request->ajax()) {
            $aff = WalletRecharge::where(['user_id' => Auth::id()])->orderBy('wallet_recharges.id', 'DESC')->with('payment:id,payment_id');
            return datatables($aff)
                ->addColumn('amount', function ($item) {
                    if (get_currency_placement() == 'after') {
                        return $item->amount . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->amount;
                    }
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == STATUS_PENDING) {
                        return '<span class="text-info">' . statusWithdrawalStatus($item->status) . '</span>';
                    } elseif ($item->status == WITHDRAWAL_STATUS_REJECTED) {
                        return '<span class="text-danger">' . statusWithdrawalStatus($item->status) . '</span>';
                    } else {
                        return '<span class="color-green">' . statusWithdrawalStatus($item->status) . '</span>';
                    }
                })->addColumn('date', function ($item) {
                    return $item->created_at->format('Y-m-d H:i:s');
                })->addColumn('payment_method', function ($item) {
                    return $item->payment_method;
                })->addColumn('transaction_id', function ($item) {
                    return $item->payment->payment_id;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }

    public function withdrawProcess(Request $request)
    {
        if ($request->amount > int_to_decimal(Auth::user()->balance)) {
            $this->showToastrMessage('warning', __('Insufficient balance'));
            return redirect()->back();
        } else {
            DB::beginTransaction();
            try {
                $beneficiary = Beneficiary::where('uuid', $request->uuid)->firstOrFail();
                $withdrow = new Withdraw();
                $withdrow->transection_id = Str::uuid()->getHex();
                $withdrow->amount = $request->amount;
                $withdrow->beneficiary_id = $beneficiary->id;
                $withdrow->save();
                Auth::user()->decrement('balance', decimal_to_int($request->amount));
                createTransaction(Auth::id(), $request->amount, TRANSACTION_WITHDRAWAL, 'Withdrawal via beneficiary ' . $beneficiary->beneficiary_name);

                $text = __("New Withdraw Request Received");
                $target_url = route('payout.new-withdraw');
                $this->send($text, 1, $target_url, null);

                $this->showToastrMessage('warming', __('Withdraw request has been saved'));
                DB::commit();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->showToastrMessage('warning', __('Something Went Wrong'));
                return redirect()->back();
            }
        }
    }

    public function myBeneficiary(Request $request)
    {
        $data['pageTitle'] = __('My Beneficiary');
        $data['navPaymentActiveClass'] = 'active';
        $data['beneficiaries'] = Beneficiary::where(['user_id' => Auth::id()])->get();
        return view('frontend.wallet.beneficiary', $data);
    }

    public function saveBeneficiary(Request $request)
    {
        if ($request->type == BENEFICIARY_CARD) {
            $rules = [
                'beneficiary_name' => 'bail|required|string|min:2',
                'card_number' => 'bail|required|numeric',
                'card_holder_name' => 'bail|required|string',
                'expire_month' => 'bail|required',
                'expire_year' => 'bail|required',
            ];
        } else if ($request->type == BENEFICIARY_BANK) {
            $rules = [
                'beneficiary_name' => 'bail|required|string|min:2',
                'bank_name' => 'bail|required',
                'bank_account_name' => 'bail|required|string',
                'bank_account_number' => 'bail|required|numeric',
                'bank_routing_number' => 'bail|required',
            ];
        } else if ($request->type == BENEFICIARY_PAYPAL) {
            $rules = [
                'beneficiary_name' => 'bail|required|string|min:2',
                'paypal_email' => 'bail|required|email'
            ];
        }

        $rules['type'] = 'required';

        $data = $request->validate($rules, [
            'beneficiary_name.required' => __('Beneficiary Name is Required'),
            'beneficiary_name.min' => __('Beneficiary Name should at least 2 char'),
            'card_number.required' => __('Card Number is Required'),
            'card_holder_name.required' => __('Card Holder Name is Required'),
            'expire_month.required' => __('Expired Month is Required'),
            'expire_year.required' => __('Expired Year is Required'),
            'bank_name.required' => __('Bank Name is Required'),
            'bank_account_name.required' => __('Bank Account Name is Required'),
            'bank_account_number.required' => __('Bank Account Number is Required'),
            'bank_routing_number.required' => __('Routing Number is Required'),
            'paypal_email.required' => __('Paypal Email is Required'),
        ]);

        Beneficiary::create($data);

        return ['message' => __('Successfully Save'), 'success' => true];
    }

    public function statusChangeBeneficiary(Beneficiary $beneficiary)
    {
        if ($beneficiary->status == PACKAGE_STATUS_ACTIVE) {
            $beneficiary->update(['status' => PACKAGE_STATUS_DISABLED]);
        } else {
            $beneficiary->update(['status' => PACKAGE_STATUS_ACTIVE]);
        }

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
        ], [
            'user_id' => Auth::id(),
            'email' => $request->email
        ]);

        $this->showToastrMessage('success', __('Update Successfully'));
        return redirect()->back();
    }

    public function downloadReceipt($uuid)
    {
        $withdraw = Withdraw::whereUuid($uuid)->first();
        //        $invoice_name = 'receipt-' . $withdraw->transection_id. '.pdf';
        // make sure email invoice is checked.
        //        $customPaper = array(0, 0, 612, 792);
        //        $pdf = PDF::loadView('instructor.finance.receipt-pdf', ['withdraw' => $withdraw])->setPaper($customPaper, 'portrait');
        //        $pdf->save(public_path() . '/uploads/receipt/' . $invoice_name);
        // return $pdf->stream($invoice_name);
        //return $pdf->download($invoice_name);
        return view('instructor.finance.receipt-pdf', ['withdraw' => $withdraw]);
    }
}
