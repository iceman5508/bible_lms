<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\LessionRequest;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lesson;
use App\Models\Enrollment;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    use General, ImageSaveTrait, SendNotification, ApiStatusTrait;

    protected $model;
    protected $courseModel;
    protected $lectureModel;

    public function __construct(Course_lesson $course_lesson, Course $course, Course_lecture $course_lecture)
    {
        $this->model = new Crud($course_lesson);
        $this->courseModel = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
    }

    public function store(LessionRequest $request, $course_uuid)
    {
        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $data = [
            'course_id' => $course->id,
            'name' => $request->name,
            'short_description' => $request->short_description ?  : null,
        ];

        $data = $this->model->create($data);
        return $this->success(['uuid' => $data->uuid], __('Created successful.'));
    }

    public function storeLecture(Request $request, $course_uuid, $lesson_uuid)
    {
        if ($request->type == 'video') {
            $request->validate([
                'video_file' => ['required'],
            ]);
        } elseif ($request->type == 'youtube') {
            $request->validate([
                'youtube_url_path' => ['required'],
            ]);

            if ($request->youtube_file_duration){
                if(preg_match('/^([0-9][0-9]):[0-5][0-9]$/',$request->youtube_file_duration)) {

                } else {
                    $request->validate([
                        'youtube_file_duration' => 'date_format:H:i'
                    ]);
                }
            }
        } elseif($request->type == 'vimeo') {
            if(env('VIMEO_STATUS') == 'active') {
                $request->validate([
                    'vimeo_url_path' => 'exclude_unless:vimeo_upload_type,1|required',
                    'vimeo_url_uploaded_path' => 'exclude_unless:vimeo_upload_type,2|required',
                ]);

                if ($request->vimeo_file_duration && ($request->vimeo_upload_type == 2)){
                    if(preg_match('/^([0-9][0-9]):[0-5][0-9]$/',$request->vimeo_file_duration)) {

                    } else {
                        $request->validate([
                            'vimeo_file_duration' => 'date_format:H:i'
                        ]);
                    }
                }
            } else {
                return $this->failed([], __('At present, upload new video in vimeo service is off. Please try other way.'));
            }
        } elseif ($request->type == 'text') {
            $request->validate([
                'text_description' => 'required',
            ]);
        } elseif ($request->type == 'image') {
            $request->validate([
                'image' => 'required',
            ]);
        } elseif ($request->type == 'pdf') {
            $request->validate([
                'pdf' => 'required',
            ]);
        } elseif ($request->type == 'slide_document') {
            $request->validate([
                'slide_document' => 'required',
            ]);
        } elseif ($request->type == 'audio') {
            $request->validate([
                'audio' => 'required',
            ]);
        }

        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $lesson = $this->model->getRecordByUuid($lesson_uuid);

        $lecture = new Course_lecture();
        $lecture->fill($request->all());
        $lecture->pre_ids = ($lecture->pre_ids) ? json_encode($lecture->pre_ids) : NULL;
        $lecture->course_id = $course->id;
        $lecture->lesson_id = $lesson->id;

        if ($request->video_file && $request->type == 'video') {
            $this->saveLectureVideo($request, $lecture); // Save Video File, Path, Size, Duration
        }

        if ($request->type == 'youtube') {
            $lecture->url_path = $request->youtube_url_path;

            $lecture->file_duration = $request->youtube_file_duration;
            $lecture->file_duration_second = $this->timeToSeconds($request->youtube_file_duration);
            $lecture->file_path = null;
        }

        if ($request->type == 'vimeo') {
            if ($request->file('vimeo_url_path') && ($request->vimeo_upload_type == 1)) {
                $path = $this->uploadVimeoVideoFile($request->title, $request->file('vimeo_url_path'));
                $lecture->url_path = $path;
                $lecture->file_duration = gmdate("i:s", $request->file_duration);
                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
                $lecture->vimeo_upload_type = $request->vimeo_upload_type;
            }  elseif ($request->vimeo_url_uploaded_path && ($request->vimeo_upload_type == 2)) {
                $lecture->vimeo_upload_type = $request->vimeo_upload_type;
                $lecture->url_path = $request->vimeo_url_uploaded_path;
                $lecture->file_duration = $request->vimeo_file_duration;
                $lecture->file_duration_second = $this->timeToSeconds($request->vimeo_file_duration);
            }
            $lecture->file_path = null;
        }

        if ($request->type == 'text') {
            $lecture->text = $request->text_description;
        }

        if ($request->type == 'image') {
            $lecture->image = $request->image ? $this->saveImage('lecture', $request->image, null, null) :   null;
        }

        if ($request->type == 'pdf') {
//            $lecture->pdf = $request->pdf ? $this->uploadFile('lecture', $request->pdf, null, null) :   null;
            $file_details = $this->uploadFileWithDetails('lecture', $request->pdf);
            if ($file_details['is_uploaded']) {
                $lecture->pdf = $file_details['path'];
            }
        }

        if ($request->type == 'slide_document') {
            $lecture->slide_document = $request->slide_document;
        }

        if ($request->type == 'audio') {
            $file_details = $this->uploadFileWithDetails('lecture', $request->audio);
            if ($file_details['is_uploaded']) {
                $lecture->audio = $file_details['path'];
            }
//            $lecture->audio = $request->audio ? $this->uploadFile('lecture', $request->audio) :   null;
            try {
                $duration = gmdate("i:s", $request->file_duration);
                $lecture->file_duration = $duration;

                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
            } catch (\Exception $exception)
            {
                //
            }
        }

        $lecture->save();


        if ($course->status == 1) {
            /** ====== send notification to student ===== */
            $students = Enrollment::where('course_id', $course->id)->select('user_id')->get();
            foreach ($students as $student)
            {
                $text = __("New lesson has been added");
                $target_url = route('student.my-course.show', $course->slug);
                $this->send($text, 3, $target_url, $student->user_id);
            }
            /** ====== send notification to student ===== */
        }

        if ($course->status != 0) {
            $text = __("New lesson has been added");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return $this->success(['course_uuid' => $course->uuid, 'step' => 'lesson']);
    }

   
    private function saveLectureVideo($request, $lecture)
    {

//        $lecture->file_path = $this->uploadFile('video', $request->video_file); // new file upload into server;
        $file_details = $this->uploadFileWithDetails('video', $request->video_file);
        if ($file_details['is_uploaded']) {
            $lecture->file_path = $file_details['path'];
        }
//        $lecture->file_size = number_format(File::size($lecture->file_path) / 1048576, 2);
            try {
                $duration = gmdate("i:s", $request->file_duration);
                $lecture->file_duration = $duration;

                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second =(int)$file_duration_second;
            } catch (\Exception $exception)
            {
                //
            }

    }

    function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }
}
