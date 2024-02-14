<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRequest;
use App\Models\Course;
use App\Models\Promotion;
use App\Models\PromotionCourse;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    use General;
    protected $promotionModel, $promotionCourseModel;
    public function __construct(Promotion $promotion, PromotionCourse $promotionCourseModel)
    {
        $this->promotionModel = new Crud($promotion);
        $this->promotionCourseModel = new Crud($promotionCourseModel);
    }
    public function index()
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Promotion List';
        $data['navPromotionParentActiveClass'] = 'mm-active';
        $data['subNavPromotionIndexActiveClass'] = 'mm-active';
        $data['promotions'] =  $this->promotionModel->getOrderById('DESC', 25);

        return view('admin.promotion.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Promotion';
        $data['navPromotionParentActiveClass'] = 'mm-active';
        $data['subNavAddPromotionActiveClass'] = 'mm-active';

        return view('admin.promotion.add', $data);
    }

    public function store(PromotionRequest $request)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $promotion = new Promotion();
        $promotion->name = $request->name;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;
        $promotion->percentage = $request->percentage;
        $promotion->status = $request->status ?? 0;
        $promotion->save();

        $this->showToastrMessage('success', __('Promotion created successfully'));
        return redirect()->route('promotion.index');
    }

    public function editPromotionCourse($uuid)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Promotion Course';
        $data['navPromotionParentActiveClass'] = 'mm-active';
        $data['subNavAddPromotionActiveClass'] = 'mm-active';
        $data['promotion'] =  $this->promotionModel->getRecordByUuid($uuid);
        $data['promotionCourseIds'] =  PromotionCourse::where('promotion_id', $data['promotion']->id)->pluck('course_id')->toArray();
        $data['alreadyAddedPromotionCourseIds'] =  PromotionCourse::where('promotion_id', '!=', $data['promotion']->id)->pluck('course_id')->toArray();
        $data['courses'] =  Course::all();
        return view('admin.promotion.edit-promotion-course-list', $data);
    }

    public function show($uuid)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Show Promotion Course List';
        $data['navPromotionParentActiveClass'] = 'mm-active';
        $data['subNavAddPromotionActiveClass'] = 'mm-active';
        $data['promotion'] =  $this->promotionModel->getRecordByUuid($uuid);

        return view('admin.promotion.view', $data);
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Promotion';
        $data['navPromotionParentActiveClass'] = 'mm-active';
        $data['subNavAddPromotionActiveClass'] = 'mm-active';
        $data['promotion'] =  $this->promotionModel->getRecordByUuid($uuid);

        return view('admin.promotion.edit', $data);
    }

    public function update(PromotionRequest $request, $uuid)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $promotion = $this->promotionModel->getRecordByUuid($uuid);
        $promotion->name = $request->name;
        $promotion->start_date = $request->start_date;
        $promotion->end_date = $request->end_date;
        $promotion->percentage = $request->percentage;
        $promotion->status = $request->status;
        $promotion->save();

        $this->showToastrMessage('success', __('Promotion updated successfully'));
        return redirect()->route('promotion.index');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $promotion = $this->promotionModel->getRecordByUuid($uuid);
        if($promotion)
        {
            PromotionCourse::where('promotion_id', $promotion->id)->get()->map(function ($q) {
                $q->delete();
            });
        }

        $this->promotionModel->deleteByUuid($uuid);

        $this->showToastrMessage('success', __('Promotion deleted successfully'));
        return redirect()->back();

    }

    public function changePromotionStatus(Request $request)
    {
        if (!Auth::user()->can('manage_promotion')) {
            abort('403');
        } // end permission checking

        $promotion = $this->promotionModel->getRecordById($request->id);
        $promotion->status = $request->status;
        $promotion->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    public function addPromotionCourseList(Request $request)
    {
        $currentPromotionCourseList = PromotionCourse::where('promotion_id', $request->promotion_id)->where('course_id', $request->course_id)->first();
        if($currentPromotionCourseList) {
            return response()->json([
                'status' => '409',
                'msg' => 'Already added in promotion list!',
            ]);
        }

        $anotherPromotionCourseList = PromotionCourse::where('course_id', $request->course_id)->first();

        if ($anotherPromotionCourseList){
            return response()->json([
                'status' => '409',
                'msg' => 'Already added in another promotion list!'
            ]);
        } else {
            $promotionCourse = new PromotionCourse();
            $promotionCourse->course_id = $request->course_id;
            $promotionCourse->promotion_id = $request->promotion_id;
            $promotionCourse->save();

            return response()->json([
                'status' => '200',
                'msg' => 'Course Added in promotion list.',
                'course_id' => $request->course_id
            ]);
        }
    }

    public function removePromotionCourseList(Request $request)
    {
        $promotionCourse = PromotionCourse::where('course_id', $request->course_id)->first();
        if ($promotionCourse){
            PromotionCourse::where('course_id', $request->course_id)->delete();

            return response()->json([
                'status' => '200',
                'msg' => 'Course remove from promotion list.',
                'course_id' => $request->course_id
            ]);
        }

        return response()->json([
            'status' => '404',
            'msg' => 'Course not found!'
        ]);
    }

}
