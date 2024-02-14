<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class BlogCategoryController extends Controller
{
    use General;

    protected $model;
    public function __construct(BlogCategory $category)
    {
        $this->model = new Crud($category);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Blog Category';
        $data['navBlogActiveClass'] = "mm-active";
        $data['subNavBlogCategoryIndexActiveClass'] = "mm-active";
        $data['blogCategories'] = $this->model->getOrderById('DESC', 25);
        return view('admin.blog.category-index', $data);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required',
        ]);

        $slug = getSlug($request->name);

        if (BlogCategory::where('slug', $slug)->count() > 0)
        {
            $slug = getSlug($request->name) . '-'. rand(100000, 999999);
        }
        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status,
        ];

        $this->model->create($data); // create new blog

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        $this->model->updateByUuid($data, $uuid); // update category
        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $this->model->deleteByUuid($uuid); // delete record
        $this->showToastrMessage('error', __('Blog has been deleted'));
        return redirect()->back();
    }
}
