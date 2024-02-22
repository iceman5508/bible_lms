<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRequest;
use App\Models\Course;
use App\Models\Promotion;
use App\Models\PromotionCourse;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    use ApiStatusTrait;
    protected $promotionModel, $promotionCourseModel;
    public function __construct(Promotion $promotion, PromotionCourse $promotionCourseModel)
    {
        $this->promotionModel = new Crud($promotion);
        $this->promotionCourseModel = new Crud($promotionCourseModel);
    }
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_promotion', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['promotions'] =  $this->promotionModel->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function store(PromotionRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_promotion', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        try{
            DB::beginTransaction();
            $promotion = new Promotion();
            $promotion->name = $request->name;
            $promotion->start_date = $request->start_date;
            $promotion->end_date = $request->end_date;
            $promotion->percentage = $request->percentage;
            $promotion->status = $request->status ?? 0;
            $promotion->save();

            DB::commit();
            return $this->success([], __("Promotion created successfully"));
        }catch(\Exception $e){
            DB::rollback();
            return $this->failed([], $e->getMessage());
        }
    }

    public function editPromotionCourse($uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_promotion', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['promotion'] =  $this->promotionModel->getRecordByUuid($uuid);
        $data['promotionCourseIds'] =  PromotionCourse::where('promotion_id', $data['promotion']->id)->pluck('course_id')->toArray();
        $data['alreadyAddedPromotionCourseIds'] =  PromotionCourse::where('promotion_id', '!=', $data['promotion']->id)->pluck('course_id')->toArray();
        $data['courses'] = Course::all();
        return $this->success($data);
    }

    public function update(PromotionRequest $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_promotion', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        try{
            $promotion = $this->promotionModel->getRecordByUuid($uuid);
            $promotion->name = $request->name;
            $promotion->start_date = $request->start_date;
            $promotion->end_date = $request->end_date;
            $promotion->percentage = $request->percentage;
            $promotion->status = $request->status;
            $promotion->save();

            DB::commit();
            return $this->success([], __("Promotion updated successfully"));
        }catch(\Exception $e){
            DB::rollback();
            return $this->failed([], $e->getMessage());
        }
    }

    public function addPromotionCourseList(Request $request)
    {
        $currentPromotionCourseList = PromotionCourse::where('promotion_id', $request->promotion_id)->where('course_id', $request->course_id)->first();
        if($currentPromotionCourseList) {
            return $this->success([], __('Already added in promotion list!'));
        }

        $anotherPromotionCourseList = PromotionCourse::where('course_id', $request->course_id)->first();

        if ($anotherPromotionCourseList){
            return $this->success([], __('Already added in another promotion list!'));
        } else {
            $promotionCourse = new PromotionCourse();
            $promotionCourse->course_id = $request->course_id;
            $promotionCourse->promotion_id = $request->promotion_id;
            $promotionCourse->save();
            return $this->success([], __('Course Added in promotion list.',));
        }
    }

    public function removePromotionCourseList(Request $request)
    {
        $promotionCourse = PromotionCourse::where('course_id', $request->course_id)->first();
        if ($promotionCourse){
            PromotionCourse::where('course_id', $request->course_id)->delete();
            return $this->success([], __('Course  has been removed from promotion list.'));
        }

        return $this->error([], __('Course not found!'), 404);
    }

}
