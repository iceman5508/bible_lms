<?php

use App\Http\Controllers\Organization\AccountController;
use App\Http\Controllers\Organization\AssignmentController;
use App\Http\Controllers\Organization\BundleCourseController;
use App\Http\Controllers\Organization\CertificateController;
use App\Http\Controllers\Organization\ChatController;
use App\Http\Controllers\Organization\CourseController;
use App\Http\Controllers\Organization\ExamController;
use App\Http\Controllers\Organization\LessonController;
use App\Http\Controllers\Organization\RefundController;
use App\Http\Controllers\Organization\ResourceController;
use App\Http\Controllers\Organization\ScormController;
use App\Http\Controllers\Organization\ConsultationController;
use App\Http\Controllers\Organization\DashboardController;
use App\Http\Controllers\Organization\DiscussionController;
use App\Http\Controllers\Organization\FinanceController;
use App\Http\Controllers\Organization\FollowController;
use App\Http\Controllers\Organization\GmeetSettingController;
use App\Http\Controllers\Organization\InstructorController;
use App\Http\Controllers\Organization\LiveClassController;
use App\Http\Controllers\Organization\NoticeBoardController;
use App\Http\Controllers\Organization\ProfileController;
use App\Http\Controllers\Organization\StudentController;
use App\Http\Controllers\Organization\ZoomSettingController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('chat-messages', [ChatController::class, 'getChatMessages'])->name('chat.messages');
Route::post('chat-send', [ChatController::class, 'sendChatMessage'])->name('chat.send');

Route::prefix('instructor')->group(function () {
    Route::get('/', [InstructorController::class, 'index'])->name('instructor.index');
    Route::get('create', [InstructorController::class, 'create'])->name('instructor.create');
    Route::post('store', [InstructorController::class, 'store'])->name('instructor.store');
    Route::get('edit/{uuid}', [InstructorController::class, 'edit'])->name('instructor.edit');
    Route::post('update/{uuid}', [InstructorController::class, 'update'])->name('instructor.update');
    Route::delete('delete/{uuid}', [InstructorController::class, 'delete'])->name('instructor.delete');
    Route::post('status', [InstructorController::class, 'status'])->name('instructor.status');
});

Route::prefix('student')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('student.index');
    Route::get('create', [StudentController::class, 'create'])->name('student.create');
    Route::post('store', [StudentController::class, 'store'])->name('student.store');
    Route::get('edit/{uuid}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('update/{uuid}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('delete/{uuid}', [StudentController::class, 'delete'])->name('student.delete');
    Route::get('view/{uuid}', [StudentController::class, 'view'])->name('student.view');
    Route::post('status', [StudentController::class, 'status'])->name('student.status');
});

Route::prefix('course')->group(function () {

    Route::get('create', [CourseController::class, 'create'])->name('course.create');
    Route::get('/', [CourseController::class, 'index'])->name('course.index');
    Route::post('store', [CourseController::class, 'store'])->name('course.store');
    Route::get('edit/{uuid}', [CourseController::class, 'edit'])->name('course.edit');
    Route::post('update-overview/{uuid}', [CourseController::class, 'updateOverview'])->name('course.update.overview');
    Route::post('update-category/{uuid}', [CourseController::class, 'updateCategory'])->name('course.update.category');
    Route::get('upload-finished/{uuid}', [CourseController::class, 'uploadFinished'])->name('course.upload-finished');
    Route::delete('course-delete/{uuid}', [CourseController::class, 'delete'])->name('course.delete');

    Route::prefix('lesson')->group(function () {
        Route::post('store/{course_uuid}', [LessonController::class, 'store'])->name('lesson.store');
        Route::post('update-lesson/{course_uuid}/{lesson_id}', [LessonController::class, 'updateLesson'])->name('lesson.update');
        Route::delete('delete-lesson/{lesson_id}', [LessonController::class, 'deleteLesson'])->name('lesson.delete');

        Route::get('upload-lecture/{course_uuid}/{lesson_uuid}', [LessonController::class, 'uploadLecture'])->name('upload.lecture');
        Route::post('store-lecture/{course_uuid}/{lesson_uuid}', [LessonController::class, 'storeLecture'])->name('store.lecture');
        Route::get('edit-lecture/{course_uuid}/{lesson_uuid}/{lecture_uuid}', [LessonController::class, 'editLecture'])->name('edit.lecture');
        Route::post('update-lecture/{lecture_uuid}', [LessonController::class, 'updateLecture'])->name('update.lecture');
        Route::get('delete-lecture/{course_uuid}/{lecture_uuid}', [LessonController::class, 'deleteLecture'])->name('delete.lecture');
    });

    Route::post('store-instructor/{course_uuid}', [CourseController::class, 'storeInstructor'])->name('course.store.instructor');
    Route::prefix('scorm')->group(function () {
        Route::post('{course_uuid}', [ScormController::class, 'store'])->name('scorm.store');
    });


    Route::prefix('exam')->group(function () {
        Route::get('/{course_uuid}', [ExamController::class, 'index'])->name('exam.index');
        Route::get('create/{course_uuid}', [ExamController::class, 'create'])->name('exam.create');
        Route::post('store/{course_uuid}', [ExamController::class, 'store'])->name('exam.store');
        Route::get('edit/{uuid}', [ExamController::class, 'edit'])->name('exam.edit');
        Route::post('update/{uuid}', [ExamController::class, 'update'])->name('exam.update');
        Route::get('view/{uuid}', [ExamController::class, 'view'])->name('exam.view');
        Route::get('delete/{uuid}', [ExamController::class, 'delete'])->name('exam.delete');
        Route::get('status-change/{uuid}/{status}', [ExamController::class, 'statusChange'])->name('exam.status-change');
        Route::get('edit-mcq/{question_uuid}', [ExamController::class, 'editMcq'])->name('exam.edit-mcq');
        Route::get('question/{uuid}', [ExamController::class, 'question'])->name('exam.question');
        Route::post('save-mcq-question/{uuid}', [ExamController::class, 'saveMcqQuestion'])->name('exam.save-mcq-question');
        Route::post('bulk-upload-mcq/{uuid}', [ExamController::class, 'bulkUploadMcq'])->name('exam.bulk-upload-mcq');
        Route::post('update-mcq-question/{question_uuid}', [ExamController::class, 'updateMcqQuestion'])->name('exam.update-mcq-question');
        Route::post('save-true-false-question/{uuid}', [ExamController::class, 'saveTrueFalseQuestion'])->name('exam.save-true-false-question');
        Route::post('bulk-upload-true-false/{uuid}', [ExamController::class, 'bulkUploadTrueFalse'])->name('exam.bulk-upload-true-false');
        Route::get('edit-true-false/{question_uuid}', [ExamController::class, 'editTrueFalse'])->name('exam.edit-true-false');
        Route::post('update-true-false-question/{question_uuid}', [ExamController::class, 'updateTrueFalseQuestion'])->name('exam.update-true-false-question');
        Route::get('delete-question/{question_uuid}', [ExamController::class, 'deleteQuestion'])->name('exam.delete-question');
    });

    Route::group(['prefix' => 'assignments', 'as' => 'assignment.'], function () {
        Route::get('index/{course_uuid}', [AssignmentController::class, 'index'])->name('index');
        Route::get('create/{course_uuid}', [AssignmentController::class, 'create'])->name('create');
        Route::post('store/{course_uuid}', [AssignmentController::class, 'store'])->name('store');
        Route::get('edit/{course_uuid}/{uuid}', [AssignmentController::class, 'edit'])->name('edit');
        Route::post('update/{course_uuid}/{uuid}', [AssignmentController::class, 'update'])->name('update');
        Route::get('delete/{uuid}', [AssignmentController::class, 'delete'])->name('delete');

        Route::group(['prefix' => 'assessments', 'as' => 'assessment.'], function () {
            Route::get('index/{course_uuid}/{assignment_uuid}', [AssignmentController::class, 'assessmentIndex'])->name('index');
            Route::post('update/{assignment_submit_uuid}', [AssignmentController::class, 'assessmentSubmitMarkUpdate'])->name('update');
            Route::get('download', [AssignmentController::class, 'studentAssignmentDownload'])->name('download');
        });
    });

    Route::group(['prefix' => 'resource', 'as' => 'resource.'], function () {
        Route::get('index/{course_uuid}', [ResourceController::class, 'index'])->name('index');
        Route::get('create/{course_uuid}', [ResourceController::class, 'create'])->name('create');
        Route::post('store/{course_uuid}', [ResourceController::class, 'store'])->name('store');
        Route::get('delete/{uuid}', [ResourceController::class, 'delete'])->name('delete');
    });
});

Route::get('ranking-level-list', [DashboardController::class, 'rankingLevelList'])->name('ranking-level');

Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('save-profile/{uuid}', [ProfileController::class, 'saveProfile'])->name('save.profile');
Route::get('address', [ProfileController::class, 'address'])->name('address');
Route::post('address-update/{uuid}', [ProfileController::class, 'address_update'])->name('address.update');
Route::get('get-state-by-country/{country_id}', [ProfileController::class, 'getStateByCountry']);
Route::get('get-city-by-state/{state_id}', [ProfileController::class, 'getCityByState']);

Route::group(['prefix' => 'bundle', 'as' => ''], function () {
    Route::group(['prefix' => 'course', 'as' => 'bundle-course.'], function () {
        Route::get('index', [BundleCourseController::class, 'index'])->name('index');
        Route::get('create-step-one', [BundleCourseController::class, 'createStepOne'])->name('createStepOne');
        Route::post('store', [BundleCourseController::class, 'store'])->name('store');
        Route::get('create-step-two/{uuid}', [BundleCourseController::class, 'createEditStepTwo'])->name('createStepTwo');
        Route::get('edit-step-one/{uuid}', [BundleCourseController::class, 'editStepOne'])->name('editStepOne');
        Route::post('add-bundle-course', [BundleCourseController::class, 'addBundleCourse'])->name('addBundleCourse');
        Route::post('remove-bundle-course', [BundleCourseController::class, 'removeBundleCourse'])->name('removeBundleCourse');
        Route::get('edit', [BundleCourseController::class, 'edit'])->name('edit');
        Route::put('update/{uuid}', [BundleCourseController::class, 'update'])->name('update');
        Route::delete('delete/{uuid}', [BundleCourseController::class, 'delete'])->name('delete');
    });
});


Route::prefix('notice-board')->group(function () {
    Route::group(['prefix' => 'notice-board', 'as' => 'notice-board.'], function () {
        Route::get('course-notice-list', [NoticeBoardController::class, 'courseNoticeIndex'])->name('course-notice.index');
        Route::get('notice-board-list/{course_uuid}', [NoticeBoardController::class, 'noticeIndex'])->name('index');
        Route::get('create-notice-board/{course_uuid}', [NoticeBoardController::class, 'create'])->name('create');
        Route::get('view-notice-board/{course_uuid}/{uuid}', [NoticeBoardController::class, 'view'])->name('view');
        Route::post('notice-board-store/{course_uuid}', [NoticeBoardController::class, 'store'])->name('store');
        Route::get('edit-notice-board/{course_uuid}/{uuid}', [NoticeBoardController::class, 'edit'])->name('edit');
        Route::post('update-notice-board/{course_uuid}/{uuid}', [NoticeBoardController::class, 'update'])->name('update');
        Route::get('delete-notice-board/{uuid}', [NoticeBoardController::class, 'delete'])->name('delete');
    });
});

Route::prefix('live-class')->group(function () {
    Route::group(['prefix' => 'live-class', 'as' => 'live-class.'], function () {
        Route::get('course-live-class-list', [LiveClassController::class, 'courseLiveClassIndex'])->name('course-live-class.index');
        Route::get('live-class-list/{course_uuid}', [LiveClassController::class, 'liveClassIndex'])->name('index');
        Route::get('create-live-class/{course_uuid}', [LiveClassController::class, 'createLiveCLass'])->name('create');
        Route::post('live-class-store/{course_uuid}', [LiveClassController::class, 'store'])->name('store');
        Route::get('view-live-class/{course_uuid}/{uuid}', [LiveClassController::class, 'view'])->name('view');
        Route::get('delete-live-class/{uuid}', [LiveClassController::class, 'delete'])->name('delete');
        Route::post('get-zoom-link', [LiveClassController::class, 'getZoomMeetingLink'])->name('get-zoom-link');
    });
});

Route::prefix('finances')->group(function () {
    Route::group(['prefix' => 'finances', 'as' => 'finance.'], function () {
        Route::get('analysis', [FinanceController::class, 'analysisIndex'])->name('analysis.index');
        Route::get('withdraw-history', [FinanceController::class, 'withdrawIndex'])->name('withdraw-history.index');
        Route::get('download-receipt/{uuid}', [FinanceController::class, 'downloadReceipt'])->name('download-receipt');
        Route::post('store-withdraw', [FinanceController::class, 'storeWithdraw'])->name('store-withdraw');
    });
});

Route::prefix('account')->group(function () {
    Route::group(['prefix' => 'accounts'], function () {
        Route::get('my-card', [AccountController::class, 'myCard'])->name('my-card');
        Route::post('save-my-card', [AccountController::class, 'saveMyCard'])->name('save.my-card');
        Route::post('save-paypal', [AccountController::class, 'savePaypal'])->name('save.paypal');
    });
});

Route::group(['prefix' => 'certificates'], function () {
    Route::get('/', [CertificateController::class, 'index'])->name('certificate.index');
    Route::get('add/{course_uuid}', [CertificateController::class, 'add'])->name('certificate.add');
    Route::post('set-for-create/{course_uuid}', [CertificateController::class, 'setForCreate'])->name('certificate.setForCreate');
    Route::get('create/{course_uuid}/{certificate_uuid}', [CertificateController::class, 'create'])->name('certificate.create');
    Route::post('store/{course_uuid}/{certificate_uuid}', [CertificateController::class, 'store'])->name('certificate.store');
    Route::get('edit/{uuid}', [CertificateController::class, 'edit'])->name('certificate.edit');
    Route::post('update/{uuid}', [CertificateController::class, 'update'])->name('certificate.update');
    Route::get('view/{uuid}', [CertificateController::class, 'view'])->name('certificate.view');
});

Route::get('followers', [FollowController::class,'followers'])->name('followers');
Route::get('followings', [FollowController::class,'followings'])->name('followings');

Route::get('discussion-index', [DiscussionController::class, 'index'])->name('discussion.index');
Route::get('course-discussion-list', [DiscussionController::class, 'courseDiscussionList'])->name('course-discussion.list');
Route::post('reply-discussion/{discussion_id}', [DiscussionController::class, 'courseDiscussionReply'])->name('discussion.reply');

// Route::get('all-enroll', [StudentController::class, 'allStudentIndex'])->name('all-student');


//Start:: Consultation
Route::group(['prefix' => 'consultation', 'as' => 'consultation.'], function () {
    Route::get('/', [ConsultationController::class, 'dashboard'])->name('dashboard');
    Route::post('availability-update', [ConsultationController::class, 'availabilityUpdate'])->name('availabilityUpdate');
    Route::post('slotStore', [ConsultationController::class, 'slotStore'])->name('slotStore');
    Route::get('slot-view/{day}', [ConsultationController::class, 'slotView'])->name('slotView');
    Route::delete('slot-delete/{id}', [ConsultationController::class, 'slotDelete'])->name('slotDelete');
    Route::get('day-available-status-change/{day}', [ConsultationController::class, 'dayAvailableStatusChange'])->name('dayAvailableStatusChange');
});
Route::get('booking-request', [ConsultationController::class, 'bookingRequest'])->name('bookingRequest');
Route::post('cancel-reason/{uuid}', [ConsultationController::class, 'cancelReason'])->name('cancelReason');
Route::get('booking-history', [ConsultationController::class, 'bookingHistory'])->name('bookingHistory');
Route::get('booking-status/{uuid}/{status}', [ConsultationController::class, 'bookingStatus'])->name('bookingStatus');
Route::post('booking-meeting-create/{uuid}', [ConsultationController::class, 'bookingMeetingStore'])->name('bookingMeetingStore');
//End:: Consultation

Route::get('zoom-setting', [ZoomSettingController::class, 'zoomSetting'])->name('zoom-setting');
Route::post('zoom-setting', [ZoomSettingController::class, 'zoomSettingUpdate'])->name('zoom-setting.update');

Route::get('gmeet-setting', [GmeetSettingController::class, 'gMeetSetting'])->name('gmeet_setting');
Route::post('gmeet-setting', [GmeetSettingController::class, 'gMeetSettingUpdate'])->name('gmeet_setting.update');

Route::get('refund', [RefundController::class,'index'])->name('refund.index');
Route::post('refund-status-change', [RefundController::class,'processRefund'])->name('refund.status-change');
