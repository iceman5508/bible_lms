<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Peopleaps\Scorm\Manager\ScormManager;
use Peopleaps\Scorm\Model\ScormModel;
use App\Tools\Repositories\Crud;
use App\Traits\AicciManager;
use App\Traits\General;
use Exception;
use Illuminate\Support\Facades\Log;
use Peopleaps\Scorm\Exception\InvalidScormArchiveException;
use Peopleaps\Scorm\Exception\StorageNotFoundException;

class ScormController extends Controller
{
    use General, AicciManager;

    /** @var ScormManager $scormManager */
    private $scormManager;
    protected $courseModel;
    /**
     * ScormController constructor.
     * @param ScormManager $scormManager
     */
    public function __construct(ScormManager $scormManager, Course $course)
    {
        $this->scormManager = $scormManager;
        $this->courseModel = new Crud($course);
    }

    public function show($id)
    {
        $item = ScormModel::with('scos')->findOrFail($id);
        // response helper function from base controller reponse json.
        return $this->respond($item);
    }

    public function store(Request $request, $course_uuid)
    {
        if ($request->duration){
            if(preg_match('/^([0-9][0-9]):[0-5][0-9]$/',$request->duration)) {

            } else {
                $request->validate([
                    'duration' => 'date_format:H:i:s'
                ]);
            }
        }

        $course = $this->courseModel->getRecordByUuid($course_uuid);
        if ($request->scorm_upload) {
            try {
                $oldScorm = ScormModel::where('course_id', $course->id)->first();
                if ($oldScorm) {
                    $oldScorm->course_id = NULL;
                    $oldScorm->save();
                }

                $zip = new \ZipArchive();
                $openValue = $zip->open($request->file('scorm_file'));
                $isScormArchive = (true === $openValue) && $zip->getStream('imsmanifest.xml');
                $zip->close();

                if (!file_exists(public_path('scorm'))) {
                    mkdir(public_path('scorm'), 0755, true);
                }

                if ($isScormArchive) {
                    $oldScormUuid = ($oldScorm) ? $oldScorm->uuid : NULL;
                    if($oldScormUuid){
                        $scormOld = ScormModel::where('uuid', $oldScormUuid)->first();
                        $this->deleteScormData($scormOld);
                    }

                    $scorm = $this->scormManager->uploadScormArchive($request->file('scorm_file'), NULL);
                }
                else{
                    $oldScormUuid = ($oldScorm) ? $oldScorm->uuid : NULL;
                    $oldScormUuid = ($oldScorm) ? $oldScorm->uuid : NULL;
                    if($oldScormUuid){
                        $scormOld = ScormModel::where('uuid', $oldScormUuid)->first();
                        $this->deleteScormData($scormOld);
                    }

                    $scorm = $this->uploadScormArchive($request->file('scorm_file'), NULL);
                    $scorm->title = $course->title;
                }


                if ($scorm) {
                    $scorm->course_id = $course->id;
                    $scorm->duration = $request->duration;
                    $scorm->duration_in_second = $this->timeToSeconds($request->duration);
                    $scorm->save();
                }

                // handle scorm runtime error msg
            } catch (InvalidScormArchiveException | StorageNotFoundException $ex) {
                $this->showToastrMessage('error', $ex->getMessage());
                return redirect()->back();
            }
        }
        else{
            
            if ($course->scorm_course) {
                $course->scorm_course->course_id = $course->id;
                $course->scorm_course->duration = $request->duration;
                $course->scorm_course->duration_in_second = $this->timeToSeconds($request->duration);
                $course->scorm_course->save();
            }
        }

        return redirect(route('instructor.course.edit', [$course->uuid, 'step=instructors']));
    }

    function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }

    private function deleteScormData($model)
    {
        // Delete after the previous item is stored
        $oldScos = $model->scos()->get();

        // Delete all tracking associate with sco
        foreach ($oldScos as $oldSco) {
            $oldSco->scoTrackings()->delete();
        }
        $model->scos()->delete(); // delete scos
        // Delete folder from server
        $this->deleteScormFolder($model->uuid);
    }

    /**
     * @param $folderHashedName
     * @return bool
     */
    protected function deleteScormFolder($folderHashedName)
    {
        return $this->deleteScorm($folderHashedName);
    }

      /**
     * @param string $directory
     * @return bool
     */
    public function deleteScorm($uuid)
    {
        $this->deleteScormArchive($uuid); // try to delete archive if exists.
        return $this->deleteScormContent($uuid);
    }

    /**
     * @param string $directory
     * @return bool
     */
    private function deleteScormContent($folderHashedName)
    {
        try {
            return $this->getDisk()->deleteDirectory($folderHashedName);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    /**
     * @param string $directory
     * @return bool
     */
    private function deleteScormArchive($uuid)
    {
        try {
            return $this->getArchiveDisk()->deleteDirectory($uuid);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function saveProgress(Request $request)
    {
        // TODO save user progress...
    }
}
