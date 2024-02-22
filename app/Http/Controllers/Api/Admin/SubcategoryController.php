<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use Auth;

class SubcategoryController extends Controller
{
    use ApiStatusTrait;

    protected $model;
    protected $categoryModel;

    public function __construct(Subcategory $subcategory, Category $category)
    {
        $this->model = new Crud($subcategory);
        $this->categoryModel = new Crud($category);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_course_subcategory', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['subcategories'] = Subcategory::orderBy('id', 'DESC')->with('category')->paginate(25);;
        return $this->success($data);
    }

    public function store(SubcategoryRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_subcategory', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];

        $this->model->create($data); // create new category

        return $this->success([], __('Save successfully'));
    }

    public function update(SubcategoryRequest $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_course_subcategory', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];
        $this->model->updateByUuid($data, $uuid); // update category

        return $this->success([], __('Update successfully'));
    }
}
