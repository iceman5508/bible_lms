<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Auth;

class ContactUsController extends Controller
{
    use General;
    protected $model;
    public function __construct(ContactUs $contactUs)
    {
        $this->model = new Crud($contactUs);
    }

    public function contactUsIndex()
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Contact Us List';
        $data['navContactUsParentActiveClass'] = 'mm-active';
        $data['navContactUsParentShowClass'] = 'mm-show';
        $data['subNavContactUsIndexActiveClass'] = 'mm-active';

        $data['contactUss'] = $this->model->getOrderById('DESC', 25);
        return view('admin.contact.index', $data);
    }

    public function contactUsDelete($id)
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $contactUs = $this->model->getRecordById($id);
        $contactUs->delete();
        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }

    public function contactUsCMS()
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Contact Us CMS';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavContactUsSettingsActiveClass'] = 'mm-active';
        $data['contactUsSettingsActiveClass'] = 'active';

        return view('admin.application_settings.contact_us.cms', $data);
    }
}
