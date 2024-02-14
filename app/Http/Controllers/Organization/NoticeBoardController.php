<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\NoticeBoard;
use App\Models\Order_item;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    use General, SendNotification;
    protected $model, $courseModel;

    public function __construct(NoticeBoard $noticeBoard, Course $course)
    {
        $this->model = new CRUD($noticeBoard);
        $this->courseModel = new CRUD($course);
    }

    public function courseNoticeIndex()
    {
        $data['title'] = 'Notice Board';
        $data['navNoticeBoardActiveClass'] = 'active';
        $data['courses'] = Course::whereUserId(Auth::user()->id)->paginate();

        return view('organization.notice.notice-course-list', $data);
    }

    public function noticeIndex($course_uuid)
    {
        $data['title'] = 'Notice List';
        $data['navNoticeBoardActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['notices'] = NoticeBoard::whereCourseId($data['course']->id)->whereUserId(Auth::user()->id)->paginate();
        return view('organization.notice.notice-list', $data);
    }

    public function create($course_uuid)
    {
        $data['title'] = 'Add Notice';
        $data['navNoticeBoardActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        return view('organization.notice.create', $data);
    }

    public function store(Request $request, $course_uuid)
    {
        $request->validate([
            'topic' => 'required',
            'details' => 'required'
        ]);

        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $notice = new NoticeBoard();
        $notice->course_id = $course->id;
        $notice->topic = $request->topic;
        $notice->details = $request->details;
        $notice->save();

        /** ====== send notification to student ===== */
        $students = Enrollment::where('course_id', $course->id)->select('user_id')->get();
        foreach ($students as $student)
        {
            $text = __("New notice has been added");
            $target_url = route('student.my-course.show', $course->slug);
            $this->send($text, 3, $target_url, $student->user_id);
        }
        /** ====== send notification to student ===== */

        $this->showToastrMessage('success', __('Created Successfully'));
        return redirect()->route('organization.notice-board.index', $course_uuid);
    }

    public function view($course_uuid, $uuid)
    {
        $data['title'] = 'Notice View';
        $data['navNoticeBoardActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['notice'] = $this->model->getRecordByUuid($uuid);
        return view('organization.notice.view', $data);
    }

    public function edit($course_uuid, $uuid)
    {
        $data['title'] = 'Edit Notice';
        $data['navNoticeBoardActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['notice'] = $this->model->getRecordByUuid($uuid);
        return view('organization.notice.edit', $data);

    }

    public function update(Request $request, $course_uuid, $uuid)
    {
        $request->validate([
            'topic' => 'required',
            'details' => 'required'
        ]);

        $notice = $this->model->getRecordByUuid($uuid);
        $notice->topic = $request->topic;
        $notice->details = $request->details;
        $notice->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('organization.notice-board.index', $course_uuid);
    }

    public function delete($uuid)
    {
        $this->model->deleteByUuid($uuid);
        $this->showToastrMessage('error', __('Deleted Successfully'));
        return redirect()->back();
    }

}
