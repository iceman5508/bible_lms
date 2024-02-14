<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogTag;
use App\Models\Tag;
use App\Traits\General;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnArgument;

class BlogController extends Controller
{
    use General;
    public function blogAll()
    {
        $data['pageTitle'] = "Blog";
        $data['metaData'] = staticMeta(5);
        $data['blogs'] = Blog::latest()->active()->paginate(10);
        $data['recentBlogs'] = Blog::latest()->take(3)->active()->get();
        $data['blogCategories'] = BlogCategory::withCount('activeBlogs')->active()->get();
        $data['tags'] = Tag::all();

        return view('frontend.blog.blogs', $data);
    }

    public function blogDetails($slug)
    {
        $data['pageTitle'] = "Blog Details";
        $data['metaData'] = staticMeta(6);
        $data['blog'] = Blog::whereSlug($slug)->firstOrFail();
        $tag_ids = BlogTag::whereBlogId($data['blog']->id)->pluck('tag_id')->toArray();
        $data['tags'] = Tag::whereIn('id', $tag_ids)->get();
        $data['recentBlogs'] = Blog::latest()->take(3)->active()->get();
        $data['blogCategories'] = BlogCategory::withCount('blogs')->active()->get();
        $data['blogComments'] = BlogComment::active();
        $data['blogComments'] = $data['blogComments']->where('blog_id', $data['blog']->id)->whereNull('parent_id')->get();
        return view('frontend.blog.blog-details', $data);
    }

    public function categoryBlogs($slug)
    {
        $data['blogCategory'] = BlogCategory::whereSlug($slug)->firstOrFail();
        $data['pageTitle'] = $data['blogCategory']->name;
        $blog = Blog::whereBlogCategoryId($data['blogCategory']->id);
        $data['blogs'] = Blog::active()->whereBlogCategoryId($data['blogCategory']->id)->paginate(10);
        $data['recentBlogs'] = $blog->latest()->active()->take(3)->get();
        $data['blogCategories'] = BlogCategory::withCount('activeBlogs')->active()->get();
        $data['tags'] = Tag::all();

        return view('frontend.blog.category-blogs', $data);
    }


    public function blogCommentStore(Request $request)
    {
        $comment = new BlogComment();
        $comment->blog_id = $request->blog_id;
        $comment->user_id = $request->user_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->status = 1;
        $comment->save();

        $data['blogComments'] = BlogComment::active();
        $data['blogComments'] = $data['blogComments']->where('blog_id', $request->blog_id)->whereNull('parent_id')->get();
        return view('frontend.blog.render-comment-list', $data);
    }

    public function blogCommentReplyStore(Request $request)
    {
        if ($request->user_id && $request->comment){
            $comment = new BlogComment();
            $comment->blog_id = $request->blog_id;
            $comment->user_id = $request->user_id;
            $comment->name = $request->name;
            $comment->email = $request->email;
            $comment->comment = $request->comment;
            $comment->status = 1;
            $comment->parent_id = $request->parent_id;
            $comment->save();

            $this->showToastrMessage('success', __('Reply successfully.')) ;
            return redirect()->back();
        } else {
            $this->showToastrMessage('warning', __('You need to login first!')) ;
            return redirect()->back();
        }
    }

    public function searchBlogList(Request $request)
    {
        $data['blogs'] = Blog::active()->where('title', 'like', "%{$request->title}%")->get();


        return view('frontend.blog.render-search-blog-list', $data);
    }
}
