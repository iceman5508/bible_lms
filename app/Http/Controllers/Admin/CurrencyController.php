<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{
    use General;
    public function index()
    {
        $data['title'] = 'Currency Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['subNavCurrencyActiveClass'] = 'active';
        $data['currencies'] = Currency::all();

        return view('admin.application_settings.general.currency', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Currency';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGlobalSettingsActiveClass'] = 'mm-active';
        $data['subNavCurrencyActiveClass'] = 'active';
        $data['currency'] = Currency::findOrFail($id);
        return view('admin.application_settings.general.currency-edit', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code',
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);

        $currency = new Currency();
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement;
        $currency->save();

        if ($request->current_currency)
        {
            Currency::where('id', $currency->id)->update(['current_currency' => 'on']);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => 'off']);
        }


        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->route('settings.currency.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code,' . $id,
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);

        $currency = Currency::findOrfail($id);
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement;
        $currency->save();

        if ($request->current_currency)
        {
            Currency::where('id', $currency->id)->update(['current_currency' => 'on']);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => 'off']);
        }

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->route('settings.currency.index');
    }

    public function delete($id)
    {
        $item = Currency::findOrFail($id);
        $item->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }
}
