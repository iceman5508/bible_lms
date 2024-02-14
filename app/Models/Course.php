<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Peopleaps\Scorm\Model\ScormModel;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $appends = ['image_url', 'video_url'];
    protected $fillable = [
        'user_id',
        'course_type',
        'instructor_id',
        'organization_id',
        'category_id',
        'subcategory_id',
        'course_language_id',
        'difficulty_level_id',
        'title',
        'subtitle',
        'description',
        'description_footer',
        'feature_details',
        'price',
        'old_price',
        'learner_accessibility',
        'image',
        'video',
        'slug',
        'is_featured',
        'status',
        'drip_content',
        'access_period',
        'intro_video_check',
        'youtube_video_id',
        'is_subscription_enable',
        'private_mode',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    public function getImageUrlAttribute()
    {
        return getImageFile($this->image_path);
    }

    public function getVideoUrlAttribute()
    {
        return getVideoFile($this->video);
    }
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function course_instructors()
    {
        return $this->hasMany(CourseInstructor::class, 'course_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function language()
    {
        return $this->belongsTo(Course_language::class, 'course_language_id');
    }

    public function difficultyLevel()
    {
        return $this->belongsTo(Difficulty_level::class, 'difficulty_level_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tags', 'course_id', 'tag_id');
    }

    public function keyPoints()
    {
        return $this->hasMany(LearnKeyPoint::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Course_lesson::class, 'course_id');
    }

    public function lectures()
    {
        return $this->hasMany(Course_lecture::class, 'course_id');
    }

    public function notices()
    {
        return $this->hasMany(NoticeBoard::class, 'course_id');
    }

    public function liveClasses()
    {
        return $this->hasMany(LiveClass::class, 'course_id');
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function studentCertificate()
    {
        return $this->hasOne(Student_certificate::class, 'course_id')->where('user_id', auth()->id());
    }

    public function resources()
    {
        return $this->hasMany(CourseResource::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignmentFiles()
    {
        return $this->hasManyThrough(AssignmentFile::class, Assignment::class);
    }

    public function scorm_course()
    {
        return $this->hasOne(ScormModel::class, 'course_id');
    }

    /*
     * Video duration For All without filter in frontend
     */
    public function getVideoDurationAttribute()
    {
        $video_duration = 0;
        $total_video_duration_in_seconds = 0;

        if ($this->lectures->count() > 0) {
            foreach ($this->lectures as $lecture) {
                if ($lecture->file_duration_second) {
                    $total_video_duration_in_seconds +=  $lecture->file_duration_second;
                }
            }

            $h = floor($total_video_duration_in_seconds / 3600);
            $m = floor($total_video_duration_in_seconds % 3600 / 60);
            $s = floor($total_video_duration_in_seconds % 3600 % 60);

            if ($h > 0) {
                return "$h h $m m $s s";
            } elseif ($m > 0) {
                return "$m min $s sec";
            } elseif ($s > 0) {
                return "$s sec";
            }
        }

        return $video_duration;
    }

    /*
     * for filter in front
     */

    public function getFilterVideoDurationAttribute()
    {
        $video_duration = 0;
        $total_video_duration_seconds = 0;

        if ($this->lectures->count() > 0) {
            foreach ($this->lectures as $lecture) {
                if ($lecture->file_duration_second) {
                    $total_video_duration_seconds +=  $lecture->file_duration_second;
                }
            }

            $h = floor($total_video_duration_seconds / 3600);

            return $h;
        }

        return $video_duration;
    }

    public function exam()
    {
        return $this->hasOne(Exam::class, 'course_id');
    }

    public function publishedExams()
    {
        return $this->hasMany(Exam::class, 'course_id')->where('status', 1);
    }

    public function quizzes()
    {
        return $this->hasMany(Exam::class, 'course_id');
    }



    public function lectureViews()
    {
        return $this->hasMany(Course_lecture_views::class, 'course_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'course_id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
  
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }
   
    public function scopeUpcoming($query)
    {
        return $query->where('status', STATUS_UPCOMING_APPROVED);
    }

    public function getImagePathAttribute()
    {
        if ($this->image) {
            return $this->image;
        } else {
            return 'uploads/default/course.jpg';
        }
    }

    public function certificate()
    {
        return $this->hasOne(Certificate_by_instructor::class, 'course_id');
    }

    public function promotionCourse()
    {
        return $this->hasOne(PromotionCourse::class);
    }

    public function specialPromotionTagCourse()
    {
        return $this->hasOne(SpecialPromotionTagCourse::class);
    }

    protected static function booted()
    {
        self::creating(function ($model){
            $authUser = auth()->user();
            $model->uuid = Str::uuid()->toString();
            $model->user_id = $authUser->id;
            $model->instructor_id = $authUser->instructor ? $authUser->instructor->id : null;
            $model->organization_id = $authUser->organization ? $authUser->organization->id : null;
        });
    }
}
