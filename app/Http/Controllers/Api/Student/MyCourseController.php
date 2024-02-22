<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Assignment;
use App\Models\AssignmentSubmit;
use App\Models\BookingHistory;
use App\Models\Certificate;
use App\Models\Certificate_by_instructor;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\LiveClass;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Question;
use App\Models\Question_option;
use App\Models\Review;
use App\Models\Student_certificate;
use App\Models\Take_exam;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Peopleaps\Scorm\Model\ScormModel;

class MyCourseController extends Controller
{
    use ImageSaveTrait, General, ApiStatusTrait;

    public function myLearningCourseList(Request $request)
    {
        $result['enrollments'] = Enrollment::where('enrollments.user_id', auth()->id())->whereNotNull('enrollments.course_id')->select('enrollments.*', 'order_items.unit_price')->join('orders', 'orders.id', '=', 'enrollments.order_id')->join('order_items', 'order_items.order_id', '=', 'orders.id')->where('enrollments.status', ACCESS_PERIOD_ACTIVE)->groupBy('enrollments.id');

        $sortByID = $request->sortByID; // 1=newest, 2=Oldest
        if ($sortByID) {
            if ($sortByID == 1) {
                $result['enrollments'] = $result['enrollments']->latest();
            }
        } else {
            $result['enrollments'] = $result['enrollments']->latest();
        }

        $result['enrollments'] = $result['enrollments']->with('course:id,course_type,difficulty_level_id,title,subtitle,description,price,old_price,learner_accessibility,slug,status,average_rating,drip_content,image')->paginate();

        $data['enrollments'] = [];
        foreach ($result['enrollments'] as $enrollment) {
            if($enrollment->course_id){
                $enrollment['progress'] = studentCourseProgress($enrollment->course_id, $enrollment->id);
            }
            $data['enrollments'][] = $enrollment;
        }

        return $this->success($data);
    }

    public function organizationCourses(Request $request)
    {
        $data['pageTitle'] = 'Organizational Courses';

        $data['courses'] = Course::query()
            ->where('user_id', auth()->user()->student->organization->user_id)
            ->whereNotNull('organization_id')
            ->paginate(10);
        return view('frontend.student.course.organizational-courses-list', $data);
    }

    public function myConsultationList(Request $request)
    {
        $allUserOrder = Order::where('user_id', auth()->id());
        $paidOrderIds = $allUserOrder->where('payment_status', 'paid')->pluck('id')->toArray();

        $allUserOrder = Order::where('user_id', auth()->id());
        $freeOrderIds = $allUserOrder->where('payment_status', 'free')->pluck('id')->toArray();

        $orderIds = array_merge($paidOrderIds, $freeOrderIds);

        $data['orderItems'] = Order_item::whereIn('order_id', $orderIds)->whereNotNull('consultation_slot_id')->with(['order', 'consultationSlot.user', 'bookingHistory']);

        $sortByID = $request->sortByID; // 1=newest, 2=Oldest
        if ($sortByID) {
            if ($sortByID == 1) {
                $bookingOrderIds = BookingHistory::whereIn('order_id', $orderIds)->where('status', 1)->pluck('order_item_id')->toArray();
                $data['orderItems'] =  Order_item::whereIn('id', $bookingOrderIds)->paginate();
            } elseif ($sortByID == 2) {
                $bookingOrderIds = BookingHistory::whereIn('order_id', $orderIds)->where('status', 2)->pluck('order_item_id')->toArray();

                $data['orderItems'] =  Order_item::whereIn('id', $bookingOrderIds)->paginate();
            } elseif ($sortByID == 3) {
                $bookingOrderIds = BookingHistory::whereIn('order_id', $orderIds)->where('status', 3)->pluck('order_item_id')->toArray();
                $data['orderItems'] =  Order_item::whereIn('id', $bookingOrderIds)->paginate();
            } else {
                $bookingOrderIds = BookingHistory::whereIn('order_id', $orderIds)->where('status', 0)->pluck('order_item_id')->toArray();
                $data['orderItems'] =  Order_item::whereIn('id', $bookingOrderIds)->paginate();
            }
        } else {
            $data['orderItems'] = $data['orderItems']->latest()->paginate();
        }

        $orderItemsCollection = $data['orderItems']->getCollection();

        foreach ($orderItemsCollection as $item) {
            $consultationSlot = $item->consultationSlot; // Assuming consultationSlot is the name of the relationship method
            $user = $consultationSlot ? $consultationSlot->user : null;

            if ($user) {
                try {
                    $userRelation = getUserRoleRelation($user);
                    $user->author_level = get_instructor_ranking_level($user->badges);
                    $user->professional_title = $user->$userRelation->professional_title;
                } catch (Exception $e) {
                    $user->author_level = '';
                    $user->professional_title = '';
                }
            }
        }

        // Set the updated collection back to the paginator
        $data['orderItems']->setCollection($orderItemsCollection);

        return $this->success($data);
    }

    public function downloadInvoice($item_id)
    {
        $item = Order_item::find($item_id);

        $invoice_name = 'invoice' . '.pdf';
        // make sure email invoice is checked.
        //        $customPaper = array(0, 0, 612, 792);
        //        $pdf = PDF::loadView('frontend.student.course.invoice', ['item' => $item])->setPaper($customPaper, 'portrait');
        //$pdf->save(public_path() . '/uploads/receipt/' . $invoice_name);
        // return $pdf->stream($invoice_name);
        return view('frontend.student.course.invoice', ['item' => $item]);

        //        return $pdf->download($invoice_name);
    }

    public function myCourseCompleteDuration(Request $request, $course_id)
    {
        $enrollment = Enrollment::where('course_id', $course_id)->where('user_id', auth()->id())->whereDate('end_date', '>=', now())->first();
        $scorm = ScormModel::where('course_id', $course_id)->select('duration_in_second')->first();
        if ($enrollment && $enrollment->completed_time < $scorm->duration_in_second) {
            $enrollment->completed_time += $request->duration;
            $enrollment->completed_time = ($enrollment->completed_time > $scorm->duration_in_second) ? $scorm->duration_in_second : $enrollment->completed_time;
            $enrollment->save();
        }
        $data = [
            'success' => 'success'
        ];

        /** === make pdf certificate ===== */
        $data['data'] = $this->makePdfCertificate($course_id, $enrollment->id);
        /** ------- end save certificate ----------- */

        return response()->json($data);
    }


    public function myCourseShow($slug, $type = NULL)
    {
        if ($type == NULL) {
            $course = Course::whereSlug($slug)->first();

            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if (now()->gt($startDate) && now()->lt($endDate)) {
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            } elseif ($course->price <= $course->old_price) {
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            } else {
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)) {
                $filterData['cashback'] = calculateCashback($course->price);
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['uuid'] = $course->uuid;
            $filterData['user_id'] = $course->user_id;
            $filterData['course_type'] = $course->course_type;
            $filterData['instructor_id'] = $course->instructor_id;
            $filterData['category_id'] = $course->category_id;
            $filterData['subcategory_id'] = $course->subcategory_id;
            $filterData['course_language_id'] = $course->course_language_id;
            $filterData['difficulty_level_id'] = $course->difficulty_level_id;
            $filterData['subtitle'] = $course->subtitle;
            $filterData['description'] = $course->description;
            $filterData['feature_details'] = $course->feature_details;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['image'] = $course->image;
            $filterData['intro_video_check'] = $course->intro_video_check;
            $filterData['video'] = $course->video;
            $filterData['youtube_video_id'] = $course->youtube_video_id;
            $filterData['is_subscription_enable'] = $course->is_subscription_enable;
            $filterData['private_mode'] = $course->private_mode;
            $filterData['slug'] = $course->slug;
            $filterData['is_featured'] = $course->is_featured;
            $filterData['status'] = $course->status;
            $filterData['drip_content'] = $course->drip_content;
            $filterData['access_period'] = $course->access_period;
            $filterData['meta_title'] = $course->meta_title;
            $filterData['meta_description'] = $course->meta_description;
            $filterData['meta_keywords'] = $course->meta_keywords;
            $filterData['og_image'] = $course->og_image;
            $filterData['created_at'] = $course->created_at;
            $filterData['updated_at'] = $course->updated_at;
            $filterData['organization_id'] = $course->organization_id;
            $filterData['key_points'] = $course->key_points;
            $filterData['title'] = $course->title;
            $filterData['average_rating'] = $course->average_rating;
            $filterData['id'] = $course->id;
            $filterData['image_url'] = $course->image_url;
            $filterData['video_url'] = $course->video_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['total_review'] = $course->reviews->count();
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $awards = '';
            foreach ($course->$userRelation->awards as $award) {
                $awards .= ' | ' . $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data = $filterData;


            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            $course = Course::whereSlug($slug)->with('lessons.lectures')->first();
            $data['lessons'] = $course->lessons;

            $instructorCourseIds = Course::where('private_mode', '!=', 1)->where('user_id', $course->user_id)->pluck('id')->toArray();

            $instructor = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
                ->where(function ($q) {
                    $q->where('ins.status', STATUS_APPROVED)
                        ->orWhere('org.status', STATUS_APPROVED);
                })
                ->with('badges:name,type,from,to,description')
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->where('users.id', $course->user_id)
                ->first();


            $data['instructor'] = [
                "id" => $instructor->id,
                "name" => $instructor->name,
                'image_url' => $instructor->image_url,
                "email" => $instructor->email,
                "area_code" => $instructor->area_code,
                "mobile_number" => $instructor->mobile_number,
                "email_verified_at" => $instructor->email_verified_at,
                "role" => $instructor->role,
                "phone_number" => $instructor->phone_number,
                "is_affiliator" => $instructor->is_affiliator,
                "organization_id" => $instructor->organization_id,
                "uuid" => $instructor->uuid,
                "professional_title" => $instructor->professional_title,
                "postal_code" => $instructor->postal_code,
                "about_me" => $instructor->about_me,
                "gender" => $instructor->gender,
                "social_link" => $instructor->social_link,
                "slug" => $instructor->slug,
                "remove_from_web_search" => $instructor->remove_from_web_search,
                "is_offline" => $instructor->is_offline,
                "offline_message" => $instructor->offline_message,
                "consultation_available" => $instructor->consultation_available,
                "hourly_rate" => $instructor->hourly_rate,
                "hourly_old_rate" => $instructor->hourly_old_rate,
                "available_type" => $instructor->available_type,
                "approval_date" => $instructor->approval_date,
                'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
                'total_course_students' => Enrollment::where('course_id', $course->id)->count(),
                'total_instructor_students' => Enrollment::whereIn('course_id', $instructorCourseIds)->count(),
                'total_instructor_course' => $instructor->courses->count(),
                'badges' => $instructor->badges,
            ];

            $data['my_given_review'] = Review::whereUserId(auth()->id())->whereCourseId($course->id)->first();

            return $this->success($data);
        } elseif ($type == 'discussion') {
            $course = Course::whereSlug($slug)->first();
            $data = Discussion::whereCourseId($course->id)->whereNull('parent_id')->with('user')->with('replies.user')->active()->get();

            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            return $this->success($data);
        } elseif ($type == 'overview') {
            $course = Course::whereSlug($slug)->first();
            $filterData['description'] = $course->description;
            $filterData['key_points'] = $course->keyPoints;

            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            return $this->success($filterData);
        } elseif ($type == 'review') {
            $course = Course::whereSlug($slug)->first();
            $data['reviews'] = Review::where('course_id', $course->id)->with('user:id,name,image')->orderBy('id', 'DESC')->paginate(10);
            $data['five_star_count'] = Review::whereCourseId($course->id)->whereRating(5)->count();
            $data['four_star_count'] = Review::whereCourseId($course->id)->whereRating(4)->count();
            $data['three_star_count'] = Review::whereCourseId($course->id)->whereRating(3)->count();
            $data['two_star_count'] = Review::whereCourseId($course->id)->whereRating(2)->count();
            $data['first_star_count'] = Review::whereCourseId($course->id)->whereRating(1)->count();

            $data['total_reviews'] = (5 * $data['five_star_count']) + (4 * $data['four_star_count']) + (3 * $data['three_star_count']) +
                (2 * $data['two_star_count']) + (1 * $data['first_star_count']);
            $data['total_user_review'] = $data['five_star_count'] + $data['four_star_count'] + $data['three_star_count'] + $data['two_star_count'] + $data['first_star_count'];
            if ($data['total_user_review'] > 0) {
                $data['average_rating'] = $data['total_reviews']  / $data['total_user_review'];
            } else {
                $data['average_rating'] = 0;
            }

            $total_reviews = Review::whereCourseId($course->id)->count();
            if ($data['total_reviews'] > 0) {
                $data['five_star_percentage'] =  100 * ($data['five_star_count'] / $total_reviews);
                $data['four_star_percentage'] =  100 * ($data['four_star_count'] / $total_reviews);
                $data['three_star_percentage'] = 100 * ($data['three_star_count'] / $total_reviews);
                $data['two_star_percentage'] = 100 * ($data['two_star_count'] / $total_reviews);
                $data['first_star_percentage'] = 100 * ($data['first_star_count'] / $total_reviews);
            } else {
                $data['five_star_percentage'] =  0;
                $data['four_star_percentage'] =  0;
                $data['three_star_percentage'] = 0;
                $data['two_star_percentage'] = 0;
                $data['first_star_percentage'] = 0;
            }
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            return $this->success($data);
        } elseif ($type == 'resource') {
            $course = Course::whereSlug($slug)->first();
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            $data = $course->resources;

            return $this->success($data);
        } elseif ($type == 'notice') {
            $course = Course::whereSlug($slug)->first();
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            $data = $course->notices;

            return $this->success($data);
        } elseif ($type == 'live_class') {
            $course = Course::whereSlug($slug)->first();
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }


            $data['upcoming_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
                $q->whereDate('date', now());
                $q->whereTime('time', '>', now());
            })
                ->orWhere(function ($q) {
                    $q->whereDate('date', '>', now());
                })
                ->latest()->get();

            $data['current_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
                $q->whereDate('date', now());
                $q->whereTime('time', '<=', now());
                $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '>=', now());
            })
                ->latest()->get();

            $data['past_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
                $q->whereDate('date', now());
                $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '<', now());
            })
                ->orWhere(function ($q) {
                    $q->whereDate('date', '<', now());
                })
                ->latest()->get();

            return $this->success($data);
        } elseif ($type == 'assignment') {
            $course = Course::whereSlug($slug)->first();
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            $data = $course->assignments;

            return $this->success($data);
        } elseif ($type == 'quiz') {
            $course = Course::whereSlug($slug)->with('publishedExams.taken_exam', 'publishedExams.questions')->first();
            if ($course->status != 1) {
                return $this->success([], __('This course is not active'));
            }

            $data = [];
            foreach($course->publishedExams->sortByDesc('id') as $quiz){
                $data[] = [
                    "id" => $quiz->id,
                    "user_id" => $quiz->user_id,
                    "course_id" => $quiz->course_id,
                    "name" => $quiz->name,
                    "short_description" => $quiz->short_description,
                    "marks_per_question" => $quiz->marks_per_question,
                    "duration" => $quiz->duration,
                    "type" => ucfirst(str_replace("_", " ", $quiz->type)),
                    "total_question" => count($quiz->questions),
                    "status" => $quiz->status,
                    "done" => is_null($quiz->taken_exam) ? 0 : 1,
                ];
            }

            return $this->success($data);
        } else {
            return $this->error([], __('No Data Found'));
        }
    }


    public function getNextId($course_lecture_views, $course, $old_lecture, $enrollment)
    {
        $lecture = Course_lecture::where('lesson_id', $old_lecture->lesson_id)->where('id', '>', $old_lecture->id)->select('uuid', 'id', 'pre_ids', 'lesson_id')->first();
        $return = NULL;
        if ($lecture) {
            $return = $lecture;
        }

        return $return;
    }

    public function assignmentDetails(Request $request)
    {
        $data = Assignment::whereCourseId($request->course_id)->whereId($request->assignment_id)->first();
        return $this->success($data);
    }

    public function assignmentResult(Request $request)
    {
        $data['assignment'] = Assignment::whereCourseId($request->course_id)->whereId($request->assignment_id)->first();
        $data['assignmentSubmit'] = AssignmentSubmit::whereUserId(auth()->id())->whereCourseId($request->course_id)->whereAssignmentId($request->assignment_id)->first();
        return $this->success($data);
    }

    public function assignmentSubmitStore(Request $request, $course_id, $assignment_id)
    {
        $validation = Validator::make($request->all(), [
            "file" => "mimes:pdf,zip"
        ]);

        if ($validation->fails()) {
            return response()->json([
                'messages' => $validation->errors()->all(),
            ]);
        }

        try{
            DB::beginTransaction();
            if ($request->hasFile('file')) {
                $assignmentSubmit = AssignmentSubmit::whereUserId(auth()->id())->whereCourseId($course_id)->whereAssignmentId($assignment_id)->first();

                if (!$assignmentSubmit) {
                    $assignmentSubmit = new AssignmentSubmit();
                } else {
                    $this->deleteFile($assignmentSubmit->file);
                }

                $assignmentSubmit->course_id = $course_id;
                $assignmentSubmit->assignment_id = $assignment_id;

                $file_details = $this->uploadFileWithDetails('assignment_submit', $request->file);
                if (!$file_details['is_uploaded']) {
                    return response()->json([
                        'message' => 'Something went wrong! Failed to upload file',
                        'status' => '404'
                    ]);
                }
                $assignmentSubmit->file = $file_details['path'];
                $assignmentSubmit->original_filename = $file_details['original_filename'];
                $assignmentSubmit->save();
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $this->error([], __('Something Went Wrong'));
        }
        return $this->success();
    }

    public function getLeaderBoard($course_id, $exam_id)
    {
        //check enrollment
        $enrollment = Enrollment::where(['user_id' => auth()->id(), 'course_id' => $course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->count();

        if(is_null($enrollment)){
            return $this->error([], __('You donn\'t have access to this course quiz'));
        }

        $exam = Exam::where('id', $exam_id)->with('questions.options')->first();

        if(is_null($exam)){
            return $this->error([], __('Exam Not found'));
        }

        $takenExams = Take_exam::whereExamId($exam_id)->orderBy('number_of_correct_answer', 'DESC')->with(['exam.questions','user'])->take(500)->get();

        $myBoard = Take_exam::whereExamId($exam_id)->where('user_id', auth()->id())->with(['exam.questions','user'])->first();
        $data['my_board'] = [];

        if(!is_null($myBoard)){
            $data['my_board'] = [
                'exam_name' => $myBoard->exam->name,
                'total_question' => count($myBoard->exam->questions),
                'marks_per_question' => $myBoard->exam->marks_per_question,
                'total_marks' => count($myBoard->exam->questions) * $myBoard->exam->marks_per_question,
                'user_name' => $myBoard->user->name,
                'user_photo' => $myBoard->user->image_url,
                'number_of_correct_answer' => $myBoard->number_of_correct_answer,
                'student_marks' => $myBoard->number_of_correct_answer * $myBoard->exam->marks_per_question,
                'position' => get_position($exam_id)
            ];
        }

        $data['board'] = [];
        foreach($takenExams as $takenExam){
            $data['board'][] = [
                'exam_name' => $takenExam->exam->name,
                'total_question' => count($takenExam->exam->questions),
                'marks_per_question' => $takenExam->exam->marks_per_question,
                'total_marks' => count($takenExam->exam->questions) * $takenExam->exam->marks_per_question,
                'user_name' => $takenExam->user->name,
                'user_photo' => $takenExam->user->image_url,
                'number_of_correct_answer' => $takenExam->number_of_correct_answer,
                'student_marks' => $takenExam->number_of_correct_answer * $takenExam->exam->marks_per_question,
            ];
        }

        return $this->success($data);

    }

    public function getDetails($course_id, $exam_id)
    {
        //check enrollment
        $enrollment = Enrollment::where(['user_id' => auth()->id(), 'course_id' => $course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->count();

        if(is_null($enrollment)){
            return $this->error([], __('You donn\'t have access to this course quiz'));
        }

        $data = Exam::where('id', $exam_id)->with('questions.options')->first();

        if(is_null($data)){
            return $this->error([], __('Exam Not found'));
        }

        return $this->success($data);

    }

    public function getResult($course_id, $exam_id)
    {
        //check enrollment
        $enrollment = Enrollment::where(['user_id' => auth()->id(), 'course_id' => $course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->count();

        if(is_null($enrollment)){
            return $this->error([], __('You donn\'t have access to this course quiz'));
        }

        $data['quiz'] = Exam::where('id', $exam_id)->with('questions.options')->first();


        $data['answers'] = Answer::whereUserId(auth()->user()->id)->whereExamId($exam_id)->get();

        if(is_null($data['quiz'])){
            return $this->error([], __('Exam Not found'));
        }

        $myBoard = Take_exam::whereExamId($exam_id)->where('user_id', auth()->id())->with(['exam.questions','user'])->first();
        $data['my_board'] = [];

        if(!is_null($myBoard)){
            $data['my_board'] = [
                'exam_name' => $myBoard->exam->name,
                'total_question' => count($myBoard->exam->questions),
                'marks_per_question' => $myBoard->exam->marks_per_question,
                'total_marks' => count($myBoard->exam->questions) * $myBoard->exam->marks_per_question,
                'user_name' => $myBoard->user->name,
                'user_photo' => $myBoard->user->image_url,
                'number_of_correct_answer' => $myBoard->number_of_correct_answer,
                'student_marks' => $myBoard->number_of_correct_answer * $myBoard->exam->marks_per_question,
                'position' => get_position($exam_id)
            ];
        }

        return $this->success($data);

    }

    public function saveExamAnswer(Request $request)
    {
        try{
            $course = Course::whereSlug($request->course_slug)->first();
            if(is_null($course)){
                return $this->error([], __("Course Not found"));
            }
            $question = Question::whereUuid($request->question_uuid)->first();
            if(is_null($question)){
                return $this->error([], __("Question Not found"));
            }
            $exam = Exam::whereUuid($request->quiz_uuid)->first();
            if(is_null($exam)){
                return $this->error([], __("Quiz Not found"));
            }
            $option = Question_option::whereUuid($request->selected_option_uuid)->first();
            if(is_null($option)){
                return $this->error([], __("Option Not found"));
            }

            DB::beginTransaction();
            if (is_null(Take_exam::whereUserId(auth()->user()->id)->whereExamId($exam->id)->first())) {
                $take_exam = new Take_exam();
                $take_exam->exam_id = $exam->id;
                $take_exam->save();
            }else{
                $take_exam = Take_exam::whereUserId(auth()->user()->id)->whereExamId($exam->id)->first();
            }

            //check old answer
            $oldAnswer = Answer::where(['user_id' => auth()->id(), 'exam_id' => $question->exam_id, 'question_id' => $question->id])->first();

            if(!is_null($oldAnswer)){
                return $this->error([], __("Already Answered"));
            }

            $answer = new Answer();
            $answer->exam_id = $question->exam_id;
            $answer->question_id = $question->id;
            $answer->question_option_id = $option->id;
            $answer->take_exam_id = $take_exam->id;
            $answer->is_correct = $option->is_correct_answer;
            $answer->save();

            if ($option->is_correct_answer == 'yes') {
                $take_exam->increment('number_of_correct_answer');
            }
            DB::commit();
            return $this->success();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function reviewCreate(Request $request)
    {
        try {
            DB::beginTransaction();
            $review_exists_user = Review::whereUserId(auth()->id())->whereCourseId($request->course_id)->first();
            if ($review_exists_user) {
                return $this->error([], __('Already you have reviewed. Thank you.'), 302);
            }

            $request->validate([
                'course_id' => 'required',
                'rating' => 'required',
                'comment' => 'required',
            ]);

            $review = new Review();
            $review->user_id = Auth::user()->id;
            $review->course_id = $request->course_id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->save();

            // Review Calculation and Update

            $data['five_star_count'] = Review::whereCourseId($request->course_id)->whereRating(5)->count();
            $data['four_star_count'] = Review::whereCourseId($request->course_id)->whereRating(4)->count();
            $data['three_star_count'] = Review::whereCourseId($request->course_id)->whereRating(3)->count();
            $data['two_star_count'] = Review::whereCourseId($request->course_id)->whereRating(2)->count();
            $data['first_star_count'] = Review::whereCourseId($request->course_id)->whereRating(1)->count();

            $data['total_reviews'] = (5 * $data['five_star_count']) + (4 * $data['four_star_count']) + (3 * $data['three_star_count']) +
                (2 * $data['two_star_count']) + (1 * $data['first_star_count']);
            $data['total_user_review'] = $data['five_star_count'] + $data['four_star_count'] + $data['three_star_count'] + $data['two_star_count'] + $data['first_star_count'];

            if ($data['total_user_review'] > 0) {
                $average_rating = $data['total_reviews'] / $data['total_user_review'];
            } else {
                $average_rating = 0;
            }

            $course = Course::where('id', $request->course_id)->first();
            if (is_null($course)) {
                return $this->error([], __('No Data Found'));
            }

            $course->average_rating = number_format($average_rating, 1);
            $course->save();

            // End:: Review Calculation and Update

            DB::commit();
            return $this->success([], __('Review Created Successful.'));
        }catch (Exception $e){
            DB::rollBack();
            return $this->error([], __('Something went wrong.'));
        }
    }

    public function myGivenReview($courseId)
    {
        $review = Review::whereUserId(auth()->id())->whereCourseId($courseId)->first();

        if(is_null($review)){
            return $this->error([], __('You did not submit any review yet.'));
        }

        return $this->success($review, __('Review fetch Successful.'));
    }

    public function reviewPaginate(Request $request, $courseId)
    {
        $data['reviews'] = Review::whereCourseId($courseId)->latest()->paginate(3);
        $response['appendReviews'] = View::make('frontend.student.course.partial.render-partial-review-list', $data)->render();
        $response['reviews'] = Review::whereCourseId($courseId)->latest()->paginate(3);
        return response()->json($response);
    }

    public function discussionCreate(Request $request)
    {
        $discussion = new Discussion();
        $discussion->user_id = Auth::id();
        $discussion->course_id = $request->course_id;
        $discussion->comment = $request->comment;
        $discussion->status = 1;
        $discussion->comment_as = 1;
        $discussion->save();

        return $this->success();
    }

    public function discussionReply(Request $request, $id)
    {
        $discussion = new Discussion();
        $discussion->user_id = Auth::id();
        $discussion->course_id = $request->course_id;
        $discussion->comment = $request->comment;
        $discussion->status = 1;
        $discussion->parent_id = $id;
        $discussion->comment_as = 1;
        $discussion->save();

        Discussion::where('id', $id)
            ->update([
                'view' => 2
            ]);
        Discussion::where('parent_id', $id)->update([
            'view' => 2
        ]);

        return $this->success();
    }

    public function makePdfCertificate($course_id, $enrollment_id)
    {
        /** === make pdf certificate ===== */
        $course = Course::find($course_id);
        if (studentCourseProgress($course->id, $enrollment_id) == 100) {
            if (Certificate_by_instructor::where('course_id', $course->id)->count() > 0 && Student_certificate::where('course_id', $course->id)->where('user_id', auth()->id())->count() == 0) {
                $certificate_by_instructor = Certificate_by_instructor::where('course_id', $course->id)->orderBy('id', 'DESC')->first();
                $certificate = Certificate::find($certificate_by_instructor->certificate_id);
                if ($certificate) {
                    $certificate_number = $enrollment_id . '-' . mt_rand(1000000000, 9999999999);
                    $certificate->certificate_number = $certificate_number;
                    $html = view('frontend.student.course.certificate.pdf', ['certificate' => $certificate, 'certificate_by_instructor' => $certificate_by_instructor, 'course_title' => $course->title])->render();

                    $data = [
                        'html' => $html,
                        'certificate_number' => $certificate_number,
                    ];

                    return $data;
                }
            }
        }
        /** ------- end save certificate ----------- */
    }

    public function saveCertificate(Request $request)
    {
        /** === make pdf certificate ===== */
        $course = Course::find($request->course_id);
        if (studentCourseProgress($course->id, $request->enrollment_id) == 100) {
            if (Certificate_by_instructor::where('course_id', $course->id)->count() > 0 && Student_certificate::where('course_id', $course->id)->where('user_id', auth()->id())->count() == 0) {
                $certificate_by_instructor = Certificate_by_instructor::where('course_id', $course->id)->orderBy('id', 'DESC')->first();
                $certificate = Certificate::find($certificate_by_instructor->certificate_id);
                if ($certificate) {
                    $certificate_name = 'certificate-' . $course->uuid . '.png';

                    $certificateFile = $request->file;  // your base64 encoded
                    $certificateFile = str_replace('data:image/png;base64,', '', $certificateFile);
                    $certificateFile = str_replace(' ', '+', $certificateFile);
                    \File::put(public_path('/uploads/certificate/student') . '/' . $certificate_name, base64_decode($certificateFile));

                    $student_certificate = new Student_certificate();
                    // $student_certificate->path = $this->saveImage('',  base64_decode($image), 'null', 'null');
                    $student_certificate->course_id = $course->id;
                    $student_certificate->certificate_number = $request->certificate_number;
                    $student_certificate->path = "/uploads/certificate/student/$certificate_name";
                    $student_certificate->save();

                    return response()->json([
                        'status' => 200
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 500
        ]);
        /** ------- end save certificate ----------- */
    }

    public function thankYou()
    {
        $data['pageTitle'] = 'New Enrolled';
        $new_order = Order::whereUserId(auth()->id())->latest()->first();

        if ($new_order) {
            $data['orderCourses'] = Order_item::whereOrderId($new_order->id)->whereNotNull('course_id')->get();
            $data['new_consultations'] = Order_item::whereOrderId($new_order->id)->whereNotNull('consultation_slot_id')->get();
        }

        return view('frontend.thankyou', $data);
    }


    public function bigBlueButtonJoinMeeting($liveClassId)
    {
        $liveClass = LiveClass::find($liveClassId);
        if ($liveClass) {
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $liveClass->meeting_id,
                    'userName' => auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $liveClass->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Live Class is not found'));
            return redirect()->back();
        }
    }

    public function liveClass($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->first();
        if (is_null($course)) {
            return $this->error([], __('No Data Found'));
        }

        $data['upcoming_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
            $q->whereDate('date', now());
            $q->whereTime('time', '>', now());
        })
            ->orWhere(function ($q) {
                $q->whereDate('date', '>', now());
            })
            ->latest()->get();

        $data['current_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
            $q->whereDate('date', now());
            $q->whereTime('time', '<=', now());
            $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '>=', now());
        })
            ->latest()->get();

        $data['past_live_classes'] = LiveClass::whereCourseId($course->id)->where(function ($q) {
            $q->whereDate('date', now());
            $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '<', now());
        })
            ->orWhere(function ($q) {
                $q->whereDate('date', '<', now());
            })
            ->latest()->get();

        return $this->success($data);
    }

    public function courseProgress($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->first();
        if (is_null($course)) {
            return $this->error([], __('No Data Found'));
        }

        $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', auth()->id())->whereDate('end_date', '>=', now())->where('status', 1)->first();

        $data = studentCourseProgress($course->id, $enrollment->id);
        return $this->success($data);
    }

    public function doneLectures($courseSlug)
    {
        $data = Course_lecture_views::where('user_id', auth()->id())->where('course_id', $courseSlug)->get('course_lecture_id')->pluck('course_lecture_id')->toArray();
        return $this->success($data);
    }

    public function nextLecture($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->first();
        $data['course_lecture_views'] = Course_lecture_views::where('course_id', $course->id)->where('user_id', auth()->id())->get();
        $lastViews = $data['course_lecture_views']->sortByDesc('id')->first();
        if(!is_null($lastViews)){
            $data = Course_lecture::where('id', '>', $lastViews->course_lecture_id)->first();
        }else{
            $data = Course_lecture::orderBy('id', 'DESC')->first();
        }
        return $this->success($data);
    }

    public function completeLecture(Request $request)
    {
        try{
            $lecture = Course_lecture::find($request->lecture_id);
            $enrollment = Enrollment::where('course_id', $request->course_id)->where('user_id', auth()->id())->whereDate('end_date', '>=', now())->first();

            if (Course_lecture_views::where('user_id', auth()->id())->where('course_id', $lecture->course_id)->where('course_lecture_id', $lecture->id)->count() == 0) {
                $course_lecture_views = new Course_lecture_views();
                $course_lecture_views->course_id = $lecture->course_id;
                $course_lecture_views->course_lecture_id = $lecture->id;
                $course_lecture_views->enrollment_id = $enrollment->id;
                $course_lecture_views->save();
            }


            /** === make pdf certificate ===== */
            $data['data'] = $this->makePdfCertificate($lecture->course_id, $enrollment->id);
            /** ------- end save certificate ----------- */
        }
        catch(Exception $e){
            return $this->error([], $e->getMessage());
        }

        return $this->success([]);
    }

}
