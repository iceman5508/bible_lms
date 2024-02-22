<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseLanguageRequest;
use App\Models\Difficulty_level;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use Auth;

class difficultyLevelController extends Controller
{
    use ApiStatusTrait;

    protected $model;

    public function __construct(Difficulty_level $difficulty_level)
    {
        $this->model = new Crud($difficulty_level);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_course_difficulty_level', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['difficulty_levels'] = $this->model->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function store(CourseLanguageRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_difficulty_level', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $this->model->create($request->only($this->model->getModel()->fillable));
        return $this->success([], __('Save Successfully'));
    }

    public function update(CourseLanguageRequest $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_difficulty_level', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $this->model->updateByUuid($request->only($this->model->getModel()->fillable), $uuid); // update tag
        return $this->success([], __('Save Successfully'));
    }
}
