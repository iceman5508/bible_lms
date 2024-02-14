<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Traits\General;

class BankController extends Controller
{
    use General;
    public function index()
    {
        $data['title'] = 'Bank Account';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavPaymentOptionsSettingsActiveClass'] = 'mm-active';
        $data['bankSettingsActiveClass'] = 'active';

        $data['banks'] = Bank::query()->paginate(25);

        return view('admin.application_settings.bank.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:banks,name',
            'account_name' => 'required',
            'account_number' => 'required',
            'status' => 'required',
        ]);

        $bank = new Bank();
        $bank->name = $request->name;
        $bank->account_name = $request->account_name;
        $bank->account_number = $request->account_number;
        $bank->status = $request->status;
        $bank->save();

        $this->showToastrMessage('success', __('Bank Created Successful'));
        return redirect()->back();
    }

    public function status($id)
    {
        $bank = Bank::find($id);
        if ($bank) {
            $bank->status = $bank->status == 1 ? 0 : 1;
            $bank->save();
            $this->showToastrMessage('success', __('Status Updated Successful'));
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Bank Edit';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavPaymentOptionsSettingsActiveClass'] = 'mm-active';
        $data['bankSettingsActiveClass'] = 'active';

        $data['bank'] = Bank::find($id);

        return view('admin.application_settings.bank.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:banks,name,' . $id,
            'account_name' => 'required',
            'account_number' => 'required',
        ]);

        $bank = Bank::find($id);
        $bank->name = $request->name;
        $bank->account_name = $request->account_name;
        $bank->account_number = $request->account_number;
        $bank->status = $request->status;
        $bank->save();
        $this->showToastrMessage('success', __('Bank Updated Successful'));
        return redirect()->route('settings.bank.index');
    }

    public function delete($id)
    {
        $bank = Bank::find($id);
        $bank->delete();
        $this->showToastrMessage('success', __('Bank Deleted Successful'));
        return redirect()->route('settings.bank.index');
    }
}
