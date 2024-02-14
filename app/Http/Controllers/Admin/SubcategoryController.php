<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Auth;

class SubcategoryController extends Controller
{
    use  General, ImageSaveTrait;

    protected $model;
    protected $categoryModel;

    public function __construct(Subcategory $subcategory, Category $category)
    {
        $this->model = new Crud($subcategory);
        $this->categoryModel = new Crud($category);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Subcategory';
        $data['subcategories'] = $this->model->getOrderById('DESC', 25);
        return view('admin.subcategory.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Subcategory';
        $data['categories'] = $this->categoryModel->all();
        return view('admin.subcategory.create', $data);
    }

    public function store(SubcategoryRequest $request)
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => getSlug($request->name),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $this->model->create($data); // create new category

        return $this->controlRedirection($request, 'subcategory', 'Subcategory');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Subcategory';
        $data['subcategory'] = $this->model->getRecordByUuid($uuid);
        $data['categories'] = $this->categoryModel->all();
        return view('admin.subcategory.edit', $data);
    }

    public function update(SubcategoryRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => getSlug($request->name),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }
        $this->model->updateByUuid($data, $uuid); // update category

        return $this->controlRedirection($request, 'subcategory', 'Subcategory');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_course_subcategory')) {
            abort('403');
        } // end permission checking

        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Subcategory has been deleted'));
        return redirect()->back();
    }

}
