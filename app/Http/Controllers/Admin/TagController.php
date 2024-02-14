<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubcategoryRequest;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class TagController extends Controller
{
    use General;

    protected $model;

    public function __construct(Tag $tag)
    {
        $this->model = new Crud($tag);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Tag';
        $data['tags'] = $this->model->getOrderById('DESC', 25);
        return view('admin.tag.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Tag';
        return view('admin.tag.create', $data);
    }

    public function store(TagRequest $request)
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking

        $data = [
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];
        $this->model->create($data); // create new category
        return $this->controlRedirection($request, 'tag', 'Tag');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Tag';
        $data['tag'] = $this->model->getRecordByUuid($uuid);
        return view('admin.tag.edit', $data);
    }

    public function update(TagRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking

        $data = [
            'name' => $request->name,
            'slug' => getSlug($request->name)
        ];
        $this->model->updateByUuid($data, $uuid); // update tag
        return $this->controlRedirection($request, 'tag', 'Tag');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_course_tag')) {
            abort('403');
        } // end permission checking


        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Tag has been deleted'));
        return redirect()->back();
    }
}
