<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubcategoryRequest;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use Auth;

class TagController extends Controller
{
    use ApiStatusTrait;

    protected $model;

    public function __construct(Tag $tag)
    {
        $this->model = new Crud($tag);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_course_tag', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['tags'] = $this->model->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function store(TagRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_tag', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data = [
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];

        $this->model->create($data); // create new category

        return $this->success([], __('Save Successfully'));
    }

    public function update(TagRequest $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_tag', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data = [
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];
        $this->model->updateByUuid($data, $uuid); // update tag

        return $this->success([], __('Updated Successfully'));
    }
}
