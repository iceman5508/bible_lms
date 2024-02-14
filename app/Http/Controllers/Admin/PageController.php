<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageStoreRequest;
use App\Models\Page;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use General, ImageSaveTrait;
    public function index(Request $request)
    {
        $data['title'] = 'Page List';
        $data['navPageParentActiveClass'] = 'mm-active';
        $data['subNavPageIndexActiveClass'] = 'mm-active';
        $data['pages'] = Page::paginate(25);

        return view('admin.page.list', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Page';
        $data['navPageParentActiveClass'] = 'mm-active';
        $data['subNavPageAddActiveClass'] = 'mm-active';

        return view('admin.page.create', $data);
    }

    public function store(PageStoreRequest $request)
    {
        if(Page::where('slug', $request->slug)->exists()) {
            $slug = $request->slug.'-'.time();
        }else{
            $slug = $request->slug;
        }

        $data = [
            'en_title' => $request->title,
            'en_description' => $request->en_description,
            'slug' => $slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        Page::create($data);

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->route('page.index');
    }

    public function edit($uuid)
    {
        $data['page'] = Page::where('uuid', $uuid)->first();
        if($data['page']) {
            $data['title'] = 'Edit Page';
            $data['navPageParentActiveClass'] = 'mm-active';
            $data['subNavPageIndexActiveClass'] = 'mm-active';

            return view('admin.page.edit', $data);
        }

        $this->showToastrMessage('error', __('Page not found!'));
        return redirect()->back();
    }

    public function update(Request $request, $uuid)
    {
        $page = Page::where('uuid', $uuid)->first();

        $request->validate([
            'title' => 'required|unique:pages,en_title,' . $page->id,
        ]);

        if(Page::where('slug', $request->slug)->exists()) {
            $slug = $request->slug.'-'.time();
        }else{
            $slug = $request->slug;
        }

        if($page) {
            $data = [
                'en_title' => $request->title ,
                'en_description' => $request->en_description,
                'slug' => $slug,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ];
    
            if($request->hasFile('og_image')){
                $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
            }

            $update = $page->update($data);

            if(!empty($update)) {
                $this->showToastrMessage('success', __('Page successfully updated'));
                return redirect()->route('page.index');
            }
        }

        $this->showToastrMessage('error', __('Something went wrong!'));
        return redirect()->back();
    }

    public function delete($uuid)
    {
        $page = Page::where('uuid', $uuid)->delete();
        if(!empty($page)) {
            $this->showToastrMessage('success', __('Page successfully deleted'));
            return redirect()->back();
        }

        $this->showToastrMessage('error', __('Something went wrong!'));
        return redirect()->back();
    }

    public function pageShow($slug)
    {
        $data['page'] = Page::where('slug', $slug)->firstOrFail();
        $data['pageTitle'] = __($data['page']->en_title);
        return view('frontend.page', $data);
    }

}
