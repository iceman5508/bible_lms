<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\ProfileRequest;
use App\Models\Instructor;
use App\Models\Instructor_awards;
use App\Models\Instructor_certificate;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use  ImageSaveTrait, ApiStatusTrait;

    protected $model;
    protected $userModel;

    public function __construct(Instructor $instructor, User $user)
    {
        $this->model = new Crud($instructor);
        $this->userModel = new Crud($user);
    }

    public function saveProfile(ProfileRequest $request)
    {
        try{
            DB::beginTransaction();
            $user = $this->userModel->getRecordById(auth()->id());
            $instructor = $user->instructor;

            if ($request->image) {
                $this->deleteFile($user->image); // delete file from server
    
                $image = $this->saveImage('user', $request->image, null, null); // new file upload into server
    
            } else {
                $image = $user->image;
            }
    
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->image = $image;
            $user->save();
    
            $instructor_date = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'professional_title' => $request->professional_title,
                'phone_number' => $request->phone_number,
                'about_me' => $request->about_me,
                'social_link' => json_encode($request->social_link),
                'gender' => $request->gender,
            ];
    
            $instructor = $this->model->updateByUuid($instructor_date, $instructor->uuid); // update category
    
            $instructor->skills()->sync($request->skills); // Skills
    
            /**
             * manage instructor certificate
             */
    
            $certificate_title = $request->certificate_title;
            $certificate_date = $request->certificate_date;
            if ($certificate_title && $certificate_date) {
                Instructor_certificate::where('instructor_id', $instructor->id)->delete();
                for ($c = 0; $c < count($certificate_title); $c++) {
                    if ($certificate_title[$c] != '' && $certificate_date[$c] != '') {
                        $certificate = [
                            'instructor_id' => $instructor->id,
                            'name' => $certificate_title[$c],
                            'passing_year' => $certificate_date[$c]
                        ];
                        Instructor_certificate::create($certificate);
                    }
                }
            }
    
            /**
             * end manage instructor certificate
             */
    
            /**
             * manage instructor award
             */
    
            $award_title = $request->award_title;
            $award_year = $request->award_year;
            if ($award_title && $award_year) {
                Instructor_awards::where('instructor_id', $instructor->id)->delete();
                for ($a = 0; $a < count($award_title); $a++) {
                    if ($award_title[$a] != '' && $award_year[$a] != '') {
                        $award = [
                            'instructor_id' => $instructor->id,
                            'name' => $award_title[$a],
                            'winning_year' => $award_year[$a]
                        ];
                        Instructor_awards::create($award);
                    }
                }
            }
    
            /**
             * end instructor award
             */

            DB::commit();
            return $this->success();
        }
        catch(\Exception $e){
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }

    }

}
