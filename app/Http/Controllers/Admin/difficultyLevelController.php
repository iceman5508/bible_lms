<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseLanguageRequest;
use App\Models\Difficulty_level;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Auth;

class difficultyLevelController extends Controller
{
    use General;

    protected $model;

    public function __construct(Difficulty_level $difficulty_level)
    {
        $this->model = new Crud($difficulty_level);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Difficulty Level';
        $data['difficulty_levels'] = $this->model->getOrderById('DESC', 25);
        return view('admin.course.level.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Difficulty Level';
        return view('admin.course.level.create', $data);
    }

    public function store(CourseLanguageRequest $request)
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking

        $this->model->create($request->only($this->model->getModel()->fillable));
        return $this->controlRedirection($request, 'difficulty-level', 'Difficulty Level');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Difficulty Level';
        $data['difficulty_level'] = $this->model->getRecordByUuid($uuid);
        return view('admin.course.level.edit', $data);
    }

    public function update(CourseLanguageRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking

        $this->model->updateByUuid($request->only($this->model->getModel()->fillable), $uuid); // update tag
        return $this->controlRedirection($request, 'difficulty-level', 'Difficulty Level');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_course_difficulty_level')) {
            abort('403');
        } // end permission checking


        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Difficulty Level has been deleted'));
        return redirect()->back();
    }
}
