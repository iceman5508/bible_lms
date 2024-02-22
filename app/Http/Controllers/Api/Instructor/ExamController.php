<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Question_option;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    use ApiStatusTrait;

    protected $model;
    protected $courseModel;
    protected $questionModel;

    public function __construct(Exam $exam, Course $course, Question $question)
    {
        $this->model = new Crud($exam);
        $this->courseModel = new Crud($course);
        $this->questionModel = new Crud($question);
    }

    public function index($course_uuid)
    {
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['exams'] = Exam::where('course_id', $data['course']->id)->with('questions.options')->get();
        return $this->success($data);
    }

    public function store(Request $request, $course_uuid)
    {
        try {
            $request->validate([
                'name' => 'required',
                'marks_per_question' => 'required',
                'duration' => 'required',
                'type' => 'required',
            ]);

            $course = $this->courseModel->getRecordByUuid($course_uuid);

            $data = [
                'course_id' => $course->id,
                'name' => $request->name,
                'marks_per_question' => $request->marks_per_question,
                'duration' => $request->duration,
                'type' => $request->type
            ];

            $this->model->create($data);
            return $this->success([], __("Successfully Added"));
        } catch (\Exception $e) {
            return $this->failed([], $e->getMessage());
        }
    }

    public function saveMcqQuestion(Request $request, $uuid)
    {
        try {
            $request->validate([
                'name' => 'required',
                'options' => 'array',
                'options.0' => 'required',
                'options.*' => 'required',
                'is_correct_answer' => 'required',
            ]);

            $exam = $this->model->getRecordByUuid($uuid);

            $question = new Question();
            $question->name = $request->name;
            $question->exam_id = $exam->id;
            $question->save();

            /** save option  */
            foreach ($request->options ?? [] as $key => $option) {

                $question_option = new Question_option();
                $question_option->question_id = $question->id;
                $question_option->name = $option;
                if ($key == $request->is_correct_answer) {
                    $question_option->is_correct_answer = 'yes';
                }
                $question_option->save();
            }
            return $this->success([], __("Successfully Added"));
        } catch (\Exception $e) {
            return $this->failed([], $e->getMessage());
        }
    }

    public function saveTrueFalseQuestion(Request $request, $uuid)
    {
        try{
            $request->validate([
                'name' => 'required',
                'is_correct_answer' => 'required',
            ]);

            $exam = $this->model->getRecordByUuid($uuid);

            $question = new Question();

            $question->name = $request->name;
            $question->exam_id = $exam->id;
            $question->save();

            /** save option  */

            $question_option = new Question_option();
            $question_option->question_id = $question->id;
            $question_option->name = 'True';
            $question_option->is_correct_answer = $request->is_correct_answer === 1 ? 'yes' : 'no';
            $question_option->save();

            $question_option = new Question_option();
            $question_option->question_id = $question->id;
            $question_option->name = 'False';
            $question_option->is_correct_answer = $request->is_correct_answer === 0 ? 'yes' : 'no';
            $question_option->save();
            return $this->success([], __("Successfully Added"));
        } catch (\Exception $e) {
            return $this->failed([], $e->getMessage());
        }

    }
}
