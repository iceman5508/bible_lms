<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplateRequest;
use App\Http\Requests\Admin\SendMailRequest;
use App\Mail\SendMailToUser;
use App\Models\Email_template;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Auth;
use Mail;

class EmailTemplateController extends Controller
{
    use General;

    protected $model;
    public function __construct(Email_template $email_template)
    {
        $this->model = new Crud($email_template);
    }

    public function index()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Email Template';
        $data['email_templates'] = $this->model->getOrderById('DESC', 25);
        $data['navEmailActiveClass'] = 'mm-active';
        $data['navEmailParentShowClass'] = 'mm-show';
        $data['subNavEmailTemplateActiveClass'] = 'mm-active';
        return view('admin.emailTemplate.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Template';
        $data['navEmailActiveClass'] = 'mm-active';
        $data['navEmailParentShowClass'] = 'mm-show';
        $data['subNavEmailTemplateActiveClass'] = 'mm-active';
        return view('admin.emailTemplate.create', $data);
    }

    public function store(EmailTemplateRequest $request)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $this->model->create($request->only($this->model->getModel()->fillable));
        return $this->controlRedirection($request, 'email-template', 'Template');
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['navEmailActiveClass'] = 'mm-active';
        $data['navEmailParentShowClass'] = 'mm-show';
        $data['subNavEmailTemplateActiveClass'] = 'mm-active';

        $data['title'] = 'Edit Template';
        $data['template'] = $this->model->getRecordByUuid($uuid);
        return view('admin.emailTemplate.edit', $data);
    }

    public function update(EmailTemplateRequest $request, $uuid)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $this->model->updateByUuid($request->only($this->model->getModel()->fillable), $uuid); // update tag
        $this->showToastrMessage('success', __('Successfully saved'));
        return redirect()->back();
    }


    public function sendEmail()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Send Mail';
        $data['email_templates'] = $this->model->all();
        $data['navEmailActiveClass'] = 'mm-active';
        $data['navEmailParentShowClass'] = 'mm-show';
        $data['subNavSendMailActiveClass'] = 'mm-active';

        return view('admin.emailTemplate.send-mail', $data);
    }

    public function sendEmailToUser(SendMailRequest $request)
    {


        if ($request->sender_type == 'instructor')
        {
            $user_ids = Instructor::pluck('user_id')->toArray();
            $to_mails = User::whereIn('id', $user_ids)->pluck('email')->toArray();
        }

        if ($request->sender_type == 'student')
        {
            $user_ids = Student::pluck('user_id')->toArray();
            $to_mails = User::whereIn('id', $user_ids)->pluck('email')->toArray();
        }

        if ($request->sender_type == 'from_csv')
        {
         $request->validate([
                'mail_list' => 'required',
            ]);

            $to_mails = [];

            try {
                if($request->has('mail_list'))
                {
                    $filename=$_FILES["mail_list"]["tmp_name"];
                    $file = fopen($filename, "r");

                    $i=0;
                    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                    {
                        if ($i > 0)
                        {
                            $to_mails[] = $getData[0];
                        }
                        $i++;

                    }
                    fclose($file);
                }

            } catch (\Exception $exception) {
                toastrMessage('error', 'Something went wrong');
                return redirect()->back();
            }

        }

        if ($to_mails)
        {
            try {

                $template = Email_template::find($request->email_template_id);
                Mail::to(array_filter($to_mails))->send(new SendMailToUser($template));

                $this->showToastrMessage('success', __('Mail successfully send'));
                return redirect()->back();

            } catch (\Exception $exception) {
                $this->showToastrMessage('error', __($exception->getMessage()));
                return redirect()->back();
            }


        }


    }


}
