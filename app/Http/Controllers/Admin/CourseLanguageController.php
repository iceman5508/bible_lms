<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseLanguageRequest;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Course_language;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class CourseLanguageController extends Controller
{
    use General;

    protected $model;

    public function __construct(Course_language $course_language)
    {
        $this->model = new Crud($course_language);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Language';
        $data['course_languages'] = $this->model->getOrderById('DESC', 25);
        return view('admin.course.language.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Language';
        return view('admin.course.language.create', $data);
    }

    public function store(CourseLanguageRequest $request)
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking

        $this->model->create($request->only($this->model->getModel()->fillable));
        return $this->controlRedirection($request, 'course-language', 'Language');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Language';
        $data['language'] = $this->model->getRecordByUuid($uuid);
        return view('admin.course.language.edit', $data);
    }

    public function update(CourseLanguageRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking

        $this->model->updateByUuid($request->only($this->model->getModel()->fillable), $uuid); // update tag
        return $this->controlRedirection($request, 'course-language', 'Language');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_course_language')) {
            abort('403');
        } // end permission checking


        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Language has been deleted'));
        return redirect()->back();
    }
}
