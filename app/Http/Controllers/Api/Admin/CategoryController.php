<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Auth;

class CategoryController extends Controller
{
    use  ImageSaveTrait, ApiStatusTrait;

    protected $model;
    public function __construct(Category $category)
    {
        $this->model = new Crud($category);
    }
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_course_category', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['categories'] = $this->model->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function store(CategoryRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_category', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data = [
            'name' => $request->name,
            'is_feature' => $request->is_feature == 1 ? 'yes' : 'no',
            'slug' => getSlug($request->name),
            'image' => $request->image ? $this->saveImage('category', $request->image, null, null) :   null
        ];

        $this->model->create($data); // create new category
        
        return $this->success([], __('Save successfully'));
    }

    public function update(CategoryRequest $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_category', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $category = $this->model->getRecordByUuid($uuid);

        if ($request->image)
        {
            $this->deleteFile($category->image); // delete file from server

            $image = $this->saveImage('category', $request->image, null, null); // new file upload into server

        } else {
            $image = $category->image;
        }

        $data = [
            'name' => $request->name,
            'is_feature' => $request->is_feature ? 'yes' : 'no',
            'slug' => getSlug($request->name),
            'image' => $image
        ];

        $this->model->updateByUuid($data, $uuid); // update category

        return $this->success([], __('Updated Successfully'));
    }
}
