<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUsIssue;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Auth;

class ContactUsIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use General;

    protected $model;
    public function __construct(ContactUsIssue $contactUsIssue)
    {
        $this->model = new Crud($contactUsIssue);

        $this->middleware('isDemo')->only(['store','update', 'destroy']);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Contact Issue List';
        $data['navContactUsParentActiveClass'] = 'mm-active';
        $data['navContactUsParentShowClass'] = 'mm-show';
        $data['subNavContactUsIssueIndexActiveClass'] = 'mm-active';

        $data['contactUsIssues'] = $this->model->getOrderById('DESC', 25);
        return view('admin.contact.issue.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Contact Issue';
        $data['navContactUsParentActiveClass'] = 'mm-active';
        $data['navContactUsParentShowClass'] = 'mm-show';
        $data['subNavContactUsIssueAddActiveClass'] = 'mm-active';

        return view('admin.contact.issue.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $request->validate([
           'name' => 'required|string|unique:contact_us_issues|max:255'
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        $this->model->create($data);
        return $this->controlRedirection($request, 'contact.issue', 'ContactUsIssue');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Contact Issue';
        $data['navContactUsParentActiveClass'] = 'mm-active';
        $data['navContactUsParentShowClass'] = 'mm-show';
        $data['subNavContactUsIssueAddActiveClass'] = 'mm-active';

        $data['issue'] = $this->model->getRecordByUuid($uuid);

        return view('admin.contact.issue.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required|string|max:255|unique:contact_us_issues,name,'. $id ,
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];
        $this->model->update($data, $id);
        return $this->controlRedirection($request, 'contact.issue', 'Contact Us Issue');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        if (!Auth::user()->can('manage_contact')) {
            abort('403');
        } // end permission checking

        $issue = $this->model->getRecordByUuid($uuid);
        $issue->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }
}
