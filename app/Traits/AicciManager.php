<?php


namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Peopleaps\Scorm\Exception\InvalidScormArchiveException;
use Peopleaps\Scorm\Model\ScormModel;
use Illuminate\Support\Str;
use DOMDocument;
use Illuminate\Support\Facades\File;
use Peopleaps\Scorm\Exception\StorageNotFoundException;
use Peopleaps\Scorm\Manager\ScormDisk;
use Illuminate\Support\Facades\Storage;

trait AicciManager
{
    /** @var ScormDisk */
    private $scormDisk;
    /** @var string $uuid */
    private $uuid;

    /**
     * @param UploadedFile $file
     * @param null|string $uuid
     * @return ScormModel
     * @throws InvalidScormArchiveException
     */
    public function uploadScormArchive(UploadedFile $file, $uuid = null)
    {
        // $uuid is meant for user to update scorm content. Hence, if user want to update content should parse in existing uuid
        if (!empty($uuid)) {
            $this->uuid =   $uuid;
        } else {
            $this->uuid = Str::uuid();
        }

        return $this->saveScorm($file, $file->getClientOriginalName(), $uuid);
    }

    /**
     *  Checks if it is a valid scorm archive
     * 
     * @param string|UploadedFile $file zip.       
     */
    private function validatePackage($file)
    {
        $zip = new \ZipArchive();
        $openValue = $zip->open($file);
        $isScormArchive = (true === $openValue);
        $zip->close();
        if (!$isScormArchive) {
            $this->onError('invalid_archive_message');
        }
    }

    /**
     *  Save scorm data
     *
     * @param string|UploadedFile $file zip.
     * @param string $filename
     * @param null|string $uuid
     * @return ScormModel
     * @throws InvalidScormArchiveException
     */
    private function saveScorm($file, $filename, $uuid = null)
    {
        $this->scormDisk = new ScormDisk();
        $this->validatePackage($file);
        $scormData  =   $this->generateScorm($file);
        // save to db
        if (is_null($scormData) || !is_array($scormData)) {
            $this->onError('invalid_scorm_data');
        }

        // This uuid is use when the admin wants to edit existing scorm file.
        if (!empty($uuid)) {
            $this->uuid =   $uuid; // Overwrite system generated uuid
        }

        /**
         * ScormModel::whereUuid Query Builder style equals ScormModel::where('uuid',$value)
         * 
         * From Laravel doc https://laravel.com/docs/5.0/queries#advanced-wheres.
         * Dynamic Where Clauses
         * You may even use "dynamic" where statements to fluently build where statements using magic methods:
         * 
         * Examples: 
         * 
         * $admin = DB::table('users')->whereId(1)->first();
         * From laravel framework https://github.com/laravel/framework/blob/9.x/src/Illuminate/Database/Query/Builder.php'
         *  Handle dynamic method calls into the method.
         *  return $this->dynamicWhere($method, $parameters);
         **/
        // Uuid indicator is better than filename for update content or add new content.
        $scorm = ScormModel::whereUuid($this->uuid);

        // Check if scom package already exists to drop old one.
        if (!$scorm->exists()) {
            $scorm = new ScormModel();
        } else {
            $scorm = $scorm->first();
            $this->deleteScormData($scorm);
            $scorm->delete();
        }

        $scorm->uuid =   $this->uuid;
        $scorm->title =   $scormData['title'];
        $scorm->version =   $scormData['version'];
        $scorm->entry_url =   $scormData['entryUrl'];
        $scorm->identifier =   $scormData['identifier'];
        $scorm->origin_file =   $filename;
        $scorm->save();

        return  $scorm;
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
        // $this->deleteScormFolder($model->uuid);
    }

    /**
     * @param $folderHashedName
     * @return bool
     */
    protected function deleteScormFolder($folderHashedName)
    {
        return $this->scormDisk->deleteScorm($folderHashedName);
    }

    /**
     * @param string|UploadedFile $file zip.       
     */
    private function parseScormArchive($file)
    {
        $version = 'SCORM';

        $zip = new \ZipArchive();
        $openValue = $zip->open($file);
        $isScormArchive = (true === $openValue) && $zip->getStream('tincan.xml');
        $version = ($isScormArchive) ? 'xAPI' : $version;

        $isScormArchive = (true === $openValue) && $zip->getStream('cmi5.xml');
        $version = ($isScormArchive) ? 'cmi5' : $version;

        $isScormArchive = (true === $openValue) && $zip->getStream('course.au');
        $version = ($isScormArchive) ? 'AICCI' : $version;


        if ($version == 'AICCI') {
            $contents = '';
            $stream = $zip->getStream('course.au');

            while (!feof($stream)) {
                $contents .= fread($stream, 2);
            }

            $dom = new DOMDocument();

            if (!$dom->loadHTML($contents)) {
                $this->onError('cannot_load_imsmanifest_message');
            }

            $entryUrl = explode(",",  $contents)[14];
        } elseif ($version == 'xAPI') {
            $contents = '';
            $stream = $zip->getStream('tincan.xml');

            while (!feof($stream)) {
                $contents .= fread($stream, 2);
            }

            $dom = new DOMDocument();

            if (!$dom->loadXML($contents)) {
                $this->onError('cannot_load_imsmanifest_message');
            }

            $entryUrl = $dom->getElementsByTagName('launch')->item(0)->nodeValue;
        } elseif ($version == 'cmi5') {
            $contents = '';
            $stream = $zip->getStream('cmi5.xml');

            while (!feof($stream)) {
                $contents .= fread($stream, 2);
            }

            $dom = new DOMDocument();

            if (!$dom->loadXML($contents)) {
                $this->onError('cannot_load_imsmanifest_message');
            }

            $entryUrl = $dom->getElementsByTagName('url')->item(0)->nodeValue;
        }

        $zip->close();

        $data = [];
        $data['identifier'] = rand();
        $data['uuid'] = $this->uuid;
        $data['title'] = basename($file, ".php"); // to follow standard file data format
        $data['version'] = $version;
        $data['entryUrl'] =  str_replace('"', "", $entryUrl);
        return $data;
    }

    /**
     * @param string|UploadedFile $file zip.       
     * @return array
     * @throws InvalidScormArchiveException
     */
    private function generateScorm($file)
    {
        $scormData = $this->parseScormArchive($file);
        /**
         * Unzip a given ZIP file into the web resources directory.
         *
         * @param string $hashName name of the destination directory
         */

        $scorm = ScormModel::whereUuid($this->uuid);
        if ($scorm->exists()) {
            $this->deleteScormFolder($this->uuid);
        }
        $this->scormDisk->unzipper($file, $this->uuid);

        return [
            'identifier' => $scormData['identifier'],
            'uuid' => $this->uuid,
            'title' => $scormData['title'], // to follow standard file data format
            'version' => $scormData['version'],
            'entryUrl' => $scormData['entryUrl'],
        ];
    }

    /**
     * Clean resources and throw exception.
     */
    private function onError($msg)
    {
        $this->scormDisk->deleteScorm($this->uuid);
        throw new InvalidScormArchiveException($msg);
    }

    /**
     * @return FilesystemAdapter $disk
     */
    private function getArchiveDisk()
    {
        if (!config()->has('filesystems.disks.' . config('scorm.archive'))) {
            throw new StorageNotFoundException('scorm_archive_disk_not_define');
        }
        return Storage::disk(config('scorm.archive'));
    }
}
