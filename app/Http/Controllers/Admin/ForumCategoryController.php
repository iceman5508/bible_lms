<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumCategoryController extends Controller
{
    use General, ImageSaveTrait;

    protected $model;
    public function __construct(ForumCategory $category)
    {
        $this->model = new Crud($category);
    }

    public function index()
    {
        $data['title'] = 'Manage Forum Category';
        $data['navForumActiveClass'] = "mm-active";
        $data['subNavForumCategoryIndexActiveClass'] = "mm-active";
        $data['forumCategories'] = $this->model->getOrderById('DESC', 25);
        return view('admin.forum.category-list', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:forum_categories,title',
            'subtitle' => 'required',
            'logo' => 'mimes:png|file|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => getSlug($request->title),
            'status' => $request->status ?? 0,
            'logo' => $request->logo ? $this->saveImage('forumCategory', $request->logo, null, null) :   null
        ];

        $this->model->create($data); // create new blog

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function update(Request $request, $uuid)
    {
        $forumCategory = $this->model->getRecordByUuid($uuid);
        $request->validate([
            'title' => 'required|unique:forum_categories,title,' . $forumCategory->id,
            'subtitle' => 'required',
            'logo' => 'mimes:png|file|dimensions:min_width=60,min_height=60,max_width=60,max_height=60'
        ]);

        if ($request->logo) {
            $this->deleteFile($forumCategory->logo); // delete file from server
            $logo = $this->saveImage('forumCategory', $request->logo, null, null); // new file upload into server

        } else {
            $logo = $forumCategory->logo;
        }

        $slug = getSlug($request->title);

        if (ForumCategory::where('slug', $slug)->where('uuid', '!=', $uuid)->count() > 0)
        {
            $slug = getSlug($request->title) . '-'. rand(100000, 999999);
        }

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'status' => $request->status ?? 0,
            'logo' => $logo,
            'slug' => $slug
        ];

        $this->model->updateByUuid($data, $uuid); // update category
        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function delete($uuid)
    {
        $this->model->deleteByUuid($uuid); // delete record
        $this->showToastrMessage('error', __('Blog has been deleted'));
        return redirect()->back();
    }
}
