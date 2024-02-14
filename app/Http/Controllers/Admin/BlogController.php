<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogTag;
use App\Models\Tag;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class BlogController extends Controller
{
    use General, ImageSaveTrait;

    protected $model;
    public function __construct(Blog $blog)
    {
        $this->model = new Crud($blog);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Blog';
        $data['blogs'] = $this->model->getOrderById('DESC', 25);
        return view('admin.blog.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Create Blog';
        $data['blogCategories'] = BlogCategory::all();
        $data['tags'] = Tag::all();
        return view('admin.blog.create', $data);
    }

    public function store(BlogRequest $request)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        if (Blog::where('slug', $request->slug)->count() > 0)
        {
            $slug = $request->slug . '-'. rand(100000, 999999);
        } else {
            $slug = $request->slug;
        }

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'details' => $request->details,
            'blog_category_id' => $request->blog_category_id,
            'image' => $request->image ? $this->saveImage('blog', $request->image, null, null) :   null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $blog = $this->model->create($data); // create new blog

        if ($request->tag_ids){
            foreach ($request->tag_ids as $tag_id){
                $blogTag = new BlogTag();
                $blogTag->blog_id = $blog->id;
                $blogTag->tag_id = $tag_id;
                $blogTag->save();
            }
        }


        return $this->controlRedirection($request, 'blog', 'Blog');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Blog';
        $data['blog'] = $this->model->getRecordByUuid($uuid);
        $data['blogTags'] = $data['blog']->tags->pluck('tag_id')->toArray();
        $data['blogCategories'] = BlogCategory::all();
        $data['tags'] = Tag::all();
        return view('admin.blog.edit', $data);
    }

    public function update(BlogRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $blog = $this->model->getRecordByUuid($uuid);

        if ($request->image)
        {
            $this->deleteFile($blog->image); // delete file from server

            $image = $this->saveImage('blog', $request->image, null, null); // new file upload into server

        } else {
            $image = $blog->image;
        }

        if (Blog::where('slug', $request->slug)->where('uuid', '!=', $uuid)->count() > 0)
        {
            $slug = $request->slug . '-'. rand(100000, 999999);
        } else {
            $slug = $request->slug;
        }

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'details' => $request->details,
            'blog_category_id' => $request->blog_category_id,
            'image' => $image,
            'status'=> $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if($request->hasFile('og_image')){
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $blog = $this->model->updateByUuid($data, $uuid); // update category

        if ($request->tag_ids){
            BlogTag::where('blog_id', $blog->id)->delete();
            foreach ($request->tag_ids as $tag_id){
                $blogTag = new BlogTag();
                $blogTag->blog_id = $blog->id;
                $blogTag->tag_id = $tag_id;
                $blogTag->save();
            }
        }

        return $this->controlRedirection($request, 'blog', 'Blog');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $blog = $this->model->getRecordByUuid($uuid);
        BlogTag::where('blog_id', $blog->id)->delete();
        $this->deleteFile($blog->image); // delete file from server
        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Blog has been deleted'));
        return redirect()->back();
    }

    public function blogCommentList()
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $data['title'] = ' Blog Comments';
        $data['navBlogParentActiveClass'] = 'mm-active';
        $data['subNavBlogCommentListActiveClass'] = 'mm-active';

        $data['comments'] = BlogComment::paginate(25);
        return view('admin.blog.comment-list', $data);

    }

    public function changeBlogCommentStatus(Request $request)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking


        $comment = BlogComment::findOrFail($request->id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    public function blogCommentDelete($id)
    {
        if (!Auth::user()->can('manage_blog')) {
            abort('403');
        } // end permission checking

        $comment = BlogComment::findOrFail($id);
        BlogComment::where('parent_id', $id)->delete();
        $comment->delete();

        $this->showToastrMessage('error', __('Blog has been deleted'));
        return redirect()->back();
    }
}
