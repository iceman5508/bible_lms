<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\Count;

class LocationController extends Controller
{
    use General;
    public function countryIndex()
    {
        $data['title'] = 'Country Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavCountryActiveClass'] = 'active';
        $data['countries'] = Country::paginate(25);

        return view('admin.application_settings.location.country', $data);
    }

    public function countryEdit($id)
    {
        $data['title'] = 'Country Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavCountryActiveClass'] = 'active';
        $data['country'] = Country::findOrFail($id);

        return view('admin.application_settings.location.country-edit', $data);
    }

    public function countryStore(Request $request)
    {
        $request->validate([
            'short_name' => 'required',
            'country_name' => 'required|unique:countries,country_name',
            'phonecode' => 'required',
            'continent' => 'required',
        ]);

        $country = new Country();
        $country->short_name = $request->short_name;
        $country->country_name = $request->country_name;
        $country->flag = getSlug($request->short_name);
        $country->slug = getSlug($request->name);
        $country->phonecode = $request->phonecode;
        $country->continent = $request->continent;
        $country->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function countryUpdate(Request $request, $id)
    {
        $request->validate([
            'short_name' => 'required',
            'country_name' => 'required|unique:countries,country_name,' . $id,
            'phonecode' => 'required',
            'continent' => 'required',
        ]);

        $country = Country::findOrfail($id);
        $country->short_name = $request->short_name;
        $country->country_name = $request->country_name;
        $country->flag = getSlug($request->short_name);
        $country->slug = getSlug($request->name);
        $country->phonecode = $request->phonecode;
        $country->continent = $request->continent;
        $country->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function countryDelete($id)
    {
        $item = Country::findOrFail($id);
        $item->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }


    public function stateIndex()
    {
        $data['title'] = 'State Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavStateActiveClass'] = 'active';
        $data['countries'] = Country::all();
        $data['states'] = State::paginate(25);

        return view('admin.application_settings.location.state', $data);
    }

    public function stateStore(Request $request)
    {
        $request->validate([
            'country_id' => 'required',
            'name' => 'required',
        ]);

        $country = new State();
        $country->country_id = $request->country_id;
        $country->name = $request->name;
        $country->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function stateEdit($id)
    {
        $data['title'] = 'State Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavStateActiveClass'] = 'active';
        $data['countries'] = Country::all();
        $data['state'] = State::findOrFail($id);

        return view('admin.application_settings.location.state-edit', $data);
    }

    public function stateUpdate(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required',
            'name' => 'required',
        ]);

        $country = State::findOrfail($id);
        $country->country_id = $request->country_id;
        $country->name = $request->name;
        $country->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function stateDelete($id)
    {
        $item = State::findOrFail($id);
        $item->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }

    public function cityIndex()
    {
        $data['title'] = 'City Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavCityActiveClass'] = 'active';
        $data['countries'] = Country::all();
        $data['states'] = State::all();
        $data['cities'] = City::paginate(25);

        return view('admin.application_settings.location.city', $data);
    }

    public function cityStore(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'name' => 'required',
        ]);

        $country = new City();
        $country->state_id = $request->state_id;
        $country->name = $request->name;
        $country->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function cityEdit($id)
    {
        $data['title'] = 'State Setting';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavLocationSettingsActiveClass'] = 'mm-active';
        $data['subNavCityActiveClass'] = 'active';
        $data['states'] = State::all();
        $data['city'] = city::findOrFail($id);

        return view('admin.application_settings.location.city-edit', $data);
    }

    public function cityUpdate(Request $request, $id)
    {
        $request->validate([
            'state_id' => 'required',
            'name' => 'required',
        ]);

        $country = City::findOrfail($id);
        $country->state_id = $request->state_id;
        $country->name = $request->name;
        $country->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function cityDelete($id)
    {
        $item = City::findOrFail($id);
        $item->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }



}
