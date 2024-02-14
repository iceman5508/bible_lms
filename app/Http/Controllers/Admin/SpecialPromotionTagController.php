<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialPromotionTagRequest;
use App\Models\Course;
use App\Models\Promotion;
use App\Models\PromotionCourse;
use App\Models\SpecialPromotionTag;
use App\Models\SpecialPromotionTagCourse;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;

class SpecialPromotionTagController extends Controller
{
    use General;
    protected $model;
    public function __construct(SpecialPromotionTag $model)
    {
        $this->model = new Crud($model);
    }

    public function index()
    {
        $data['title'] = 'Special Promotion Tag';
        $data['navCourseActiveClass'] = "mm-active";
        $data['subNavSpecialPromotionIndexActiveClass'] = "mm-active";
        $data['specials'] = $this->model->all();
        return view('admin.course.special_promotion_tag.index', $data);
    }

    public function store(SpecialPromotionTagRequest $request)
    {

        $tag = new SpecialPromotionTag();
        $tag->name = $request->name;
        $tag->color = $request->color;
        $tag->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function update(SpecialPromotionTagRequest $request, $uuid)
    {

        $tag = $this->model->getRecordByUuid($uuid);
        $tag->name = $request->name;
        $tag->color = $request->color;
        $tag->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function delete($uuid)
    {
        $tag = $this->model->getRecordByUuid($uuid);
        if ($tag)
        {
            SpecialPromotionTagCourse::where('special_promotion_tag_id', $tag->id)->get()->map(function ($q){
                $q->delete();
            });
        }

        $tag->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }

    public function editSpecialPromotionCourse($uuid)
    {
        $data['title'] = 'Special Promotion Tag Course';
        $data['navCourseActiveClass'] = "mm-active";
        $data['subNavSpecialPromotionIndexActiveClass'] = "mm-active";
        $data['special'] = $this->model->getRecordByUuid($uuid);
        $data['promotionCourseIds'] =  SpecialPromotionTagCourse::where('special_promotion_tag_id', $data['special']->id)->pluck('course_id')->toArray();
        $data['alreadyAddedPromotionCourseIds'] =  SpecialPromotionTagCourse::where('special_promotion_tag_id', '!=', $data['special']->id)->pluck('course_id')->toArray();
        $data['courses'] =  Course::all();

        return view('admin.course.special_promotion_tag.edit-special-promotion-course-list', $data);
    }

    public function addPromotionCourseList(Request $request)
    {

        $currentPromotionCourseList = SpecialPromotionTagCourse::where('special_promotion_tag_id', $request->special_id)->where('course_id', $request->course_id)->first();
        if($currentPromotionCourseList) {
            return response()->json([
                'status' => '409',
                'msg' => __('Already added in promotion list!'),
            ]);
        }

        $anotherPromotionCourseList = SpecialPromotionTagCourse::where('course_id', $request->course_id)->first();

        if ($anotherPromotionCourseList){
            return response()->json([
                'status' => '409',
                'msg' => __('Already added in another promotion list!')
            ]);
        } else {
            $promotionCourse = new SpecialPromotionTagCourse();
            $promotionCourse->course_id = $request->course_id;
            $promotionCourse->special_promotion_tag_id = $request->special_id;
            $promotionCourse->save();

            return response()->json([
                'status' => '200',
                'msg' => __('Course Added in promotion list.'),
                'course_id' => $request->course_id
            ]);
        }
    }

    public function removePromotionCourseList(Request $request)
    {
        $promotionCourse = SpecialPromotionTagCourse::where('course_id', $request->course_id)->first();
        if ($promotionCourse){
            SpecialPromotionTagCourse::where('course_id', $request->course_id)->delete();

            return response()->json([
                'status' => '200',
                'msg' => __('Course remove from promotion list.'),
                'course_id' => $request->course_id
            ]);
        }

        return response()->json([
            'status' => '404',
            'msg' => __('Course not found!')
        ]);
    }

}
