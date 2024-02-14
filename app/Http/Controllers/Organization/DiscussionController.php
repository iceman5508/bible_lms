<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = 'Discussion';
        $data['navDiscussionActiveClass'] = "active";

        $courseIds = Discussion::whereHas('course', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereNull('parent_id')->active()->NotView()->pluck('course_id')->toArray();


        if($request->ajax()){
            $data['courses'] = Course::whereIn('id', $courseIds)->where('title', 'LIKE', "%{$request->search_title}%")->get();
            return view('organization.discussion.render-discussion-course-list', $data);
        }

        $data['first_course_id'] = Course::whereIn('id', $courseIds)->select('id')->first();
        $data['courses'] = Course::whereIn('id', $courseIds)->get();

        return view('organization.discussion.index', $data);
    }

    public function courseDiscussionList(Request $request)
    {
        $data['discussions'] = Discussion::whereCourseId($request->course_id)->whereNull('parent_id')->active()->NotView()->get();
        return view('organization.discussion.render-discussion-list', $data);
    }

    public function courseDiscussionReply(Request $request, $discussion_id)
    {
        $discussion = new Discussion();
        $discussion->user_id = Auth::id();
        $discussion->course_id = $request->course_id;
        $discussion->comment = $request->commentReply;
        $discussion->status = 1;
        $discussion->parent_id = $discussion_id;
        $discussion->comment_as = 1;
        $discussion->save();

        Discussion::where('id', $discussion_id)
            ->update([
                'view' => 1
            ]);
        Discussion::where('parent_id', $discussion_id)->update([
           'view' => 1
        ]);

        $data['discussions'] = Discussion::whereCourseId($request->course_id)->whereNull('parent_id')->active()->NotView()->get();
        return view('organization.discussion.render-discussion-list', $data);
    }
}
