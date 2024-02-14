<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Question_option;
use App\Tools\Repositories\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{

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
        $data['title'] = 'Quiz List';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['exams'] = Exam::where('course_id', $data['course']->id)->get();
        $data['navCourseActiveClass'] = 'active';
        return view('organization.course.exam.index', $data);
    }

    public function create($course_uuid)
    {
        $data['title'] = 'Create New Quiz';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        return view('organization.course.exam.create', $data);

    }

    public function store(Request $request, $course_uuid)
    {
        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $data = [
            'course_id' => $course->id,
            'name' => $request->name,
            'marks_per_question' => $request->marks_per_question,
            'duration' => $request->duration,
            'type' => $request->type
        ];

        $exam = $this->model->create($data);
        return redirect(route('organization.exam.question', [$exam->uuid]));
    }

    public function edit($uuid)
    {
        $data['title'] = 'Edit Quiz';
        $data['navCourseActiveClass'] = 'active';
        $data['exam'] = $this->model->getRecordByUuid($uuid);
        return view('organization.course.exam.edit', $data);
    }

    public function update(Request $request, $uuid)
    {

        $data = [
            'name' => $request->name,
            'marks_per_question' => $request->marks_per_question,
            'duration' => $request->duration,
            'type' => $request->type
        ];
        $exam = $this->model->updateByUuid($data, $uuid);

        toastrMessage('success', 'Quiz has been updated');
        return redirect()->back();
    }


    public function view($uuid)
    {
        $data['title'] = 'View Quiz';
        $data['navCourseActiveClass'] = 'active';
        $data['exam'] = $this->model->getRecordByUuid($uuid);
        if ($data['exam']->type == 'true_false')
        {
            return view('organization.course.exam.view-true-false', $data);
        } else {
            return view('organization.course.exam.view-multiple-choice', $data);
        }

    }


    public function statusChange($uuid, $status)
    {
        $exam = $this->model->getRecordByUuid($uuid);
        $exam->status = $status;
        $exam->save();
        toastrMessage('success', 'Status has been changed');
        return redirect()->back();
    }

    public function delete($uuid)
    {
        $exam = $this->model->getRecordByUuid($uuid);
        foreach ($exam->questions as $question)
        {
            Question_option::where('question_id', $question->id)->delete();
            $question->delete();
        }
        $exam->delete();

        toastrMessage('error', 'Quiz has been deleted');
        return redirect()->back();
    }


    public function question($uuid)
    {
        $data['title'] = 'Add Question';
        $data['navCourseActiveClass'] = 'active';
        $data['exam'] = $this->model->getRecordByUuid($uuid);

        if ($data['exam']->type == 'true_false')
        {
            return view('organization.course.exam.question.true-false', $data);
        }  else {
            return view('organization.course.exam.question.multiple-choice', $data);
        }
    }

    public function saveMcqQuestion(Request $request, $uuid)
    {
        $exam = $this->model->getRecordByUuid($uuid);

        $question = new Question();
        $question->name = $request->name;
        $question->exam_id = $exam->id;
        $question->save();

        /** save option  */

        foreach ($request->options as $key => $option)
        {

            $question_option = new Question_option();
            $question_option->question_id = $question->id;
            $question_option->name = $option;
            if ($key == $request->is_correct_answer[0])
            {
                $question_option->is_correct_answer = 'yes';
            }
            $question_option->save();
        }



        switch ($request) {
            case $request->has('save_and_add'):
                toastrMessage('success', 'Question has been saved');
                return redirect(route('organization.exam.question', [$exam->uuid]));
                break;
            default :
                toastrMessage('success', 'Question has been saved');
                return redirect()->route('organization.exam.view', [$exam->uuid]);
        }

    }

    public function bulkUploadMcq(Request $request, $uuid)
    {

        $exam = $this->model->getRecordByUuid($uuid);

        try {
            if($request->has('question_file'))
            {
                $filename=$_FILES["question_file"]["tmp_name"];
                $file = fopen($filename, "r");

                $i=0;
                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                {
                    if ($i > 0)
                    {
                        $question = new Question();
                        $question->name = $getData[0]; // question
                        $question->exam_id = $exam->id;
                        $question->save();


                        /** @var  $question_option 1 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = $getData[1];
                        if ($getData[5] == 'option_1')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();


                        /** @var  $question_option 2 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = $getData[2];
                        if ($getData[5] == 'option_2')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();

                        /** @var  $question_option 3 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = $getData[3];
                        if ($getData[5] == 'option_3')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();

                        /** @var  $question_option 4 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = $getData[4];
                        if ($getData[5] == 'option_4')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();
                    }
                    $i++;
                }
                fclose($file);


                toastrMessage('success', 'Questions has been uploaded');
                return redirect()->route('organization.exam.view', [$exam->uuid]);
            }

        } catch (\Exception $exception) {
            toastrMessage('error', 'Something went wrong');
            return redirect()->back();
        }

    }

    public function editMcq($question_uuid)
    {
        $data['title'] = 'Edit MCQ';
        $data['question'] = $this->questionModel->getRecordByUuid($question_uuid);
        $data['exam'] = $data['question']->exam;
        return view('organization.course.exam.question.edit-mcq', $data);
    }

    public function updateMcqQuestion(Request $request, $question_uuid)
    {
        $question =  $this->questionModel->getRecordByUuid($question_uuid);

        $question->name = $request->name;
        $question->save();

        /** save option  */
        Question_option::where('question_id', $question->id)->delete();

        foreach ($request->options as $key => $option)
        {
            $question_option = new Question_option();
            $question_option->question_id = $question->id;
            $question_option->name = $option;
            if ($key == $request->is_correct_answer[0])
            {
                $question_option->is_correct_answer = 'yes';
            }
            $question_option->save();
        }

        toastrMessage('success', 'MCQ has been updated');
        return redirect()->route('organization.exam.view', [$question->exam->uuid]);
    }


    public function saveTrueFalseQuestion(Request $request, $uuid)
    {
        $exam = $this->model->getRecordByUuid($uuid);

        $question = new Question();

        $question->name = $request->name;
        $question->exam_id = $exam->id;
        $question->save();

        /** save option  */

        $question_option = new Question_option();
        $question_option->question_id = $question->id;
        $question_option->name = 'True';
        $question_option->is_correct_answer = $request->is_correct_answer == 1 ? 'yes' : 'no';
        $question_option->save();

        $question_option = new Question_option();
        $question_option->question_id = $question->id;
        $question_option->name = 'False';
        $question_option->is_correct_answer = $request->is_correct_answer == '0' ? 'yes' : 'no';
        $question_option->save();



        switch ($request) {
            case $request->has('save_and_add'):
                toastrMessage('success', 'Question has been saved');
                return redirect(route('organization.exam.question', [$exam->uuid]));
                break;
            default :
                toastrMessage('success', 'Question has been saved');
                return redirect()->route('organization.exam.view', [$exam->uuid]);
        }

    }

    public function bulkUploadTrueFalse(Request $request, $uuid)
    {

        $exam = $this->model->getRecordByUuid($uuid);

        try {
            if($request->has('question_file'))
            {
                $filename=$_FILES["question_file"]["tmp_name"];
                $file = fopen($filename, "r");

                $i=0;
                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                {
                    if ($i > 0)
                    {
                        $question = new Question();
                        $question->name = $getData[0]; // question
                        $question->exam_id = $exam->id;
                        $question->save();


                        /** @var  $question_option 1 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = 'True';
                        if ($getData[3] == 'option_1')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();


                        /** @var  $question_option 2 */
                        $question_option = new Question_option();
                        $question_option->question_id = $question->id;
                        $question_option->name = 'False';
                        if ($getData[3] == 'option_2')
                        {
                            $question_option->is_correct_answer = 'yes';
                        }
                        $question_option->save();
                    }
                    $i++;
                }
                fclose($file);


                toastrMessage('success', 'Questions has been uploaded');
                return redirect()->route('organization.exam.view', [$exam->uuid]);
            }

        } catch (\Exception $exception) {
            toastrMessage('error', 'Something went wrong');
            return redirect()->back();
        }

    }

    public function editTrueFalse($question_uuid)
    {
        $data['title'] = 'Edit True False';
        $data['question'] = $this->questionModel->getRecordByUuid($question_uuid);
        $data['exam'] = $data['question']->exam;
        return view('organization.course.exam.question.edit-true-false', $data);
    }

    public function updateTrueFalseQuestion(Request $request, $question_uuid)
    {
        $question =  $this->questionModel->getRecordByUuid($question_uuid);

        $question->name = $request->name;
        $question->save();

        /** save option  */
        Question_option::where('question_id', $question->id)->delete();

        /** save option  */

        $question_option = new Question_option();
        $question_option->question_id = $question->id;
        $question_option->name = 'True';
        $question_option->is_correct_answer = $request->is_correct_answer == 1 ? 'yes' : 'no';
        $question_option->save();

        $question_option = new Question_option();
        $question_option->question_id = $question->id;
        $question_option->name = 'False';
        $question_option->is_correct_answer = $request->is_correct_answer == '0' ? 'yes' : 'no';
        $question_option->save();


        toastrMessage('success', 'Question has been updated');
        return redirect()->route('organization.exam.view', [$question->exam->uuid]);
    }


    public function deleteQuestion($question_uuid)
    {
        $question =  $this->questionModel->getRecordByUuid($question_uuid);
        /** save option  */
        Question_option::where('question_id', $question->id)->delete();
        $question->delete();
        return redirect()->back();
    }



}
