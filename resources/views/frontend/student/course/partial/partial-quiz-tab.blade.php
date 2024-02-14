<div class="tab-pane fade {{$action_type ? 'show active' : '' }}" id="Quiz" role="tabpanel" aria-labelledby="Quiz-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab instructor-quiz-list-page bg-white p-30">
                @if($course->publishedExams->count() > 0)

                    @if(!$action_type)
                        @include('frontend.student.course.partial.render-quiz-list')
                    @elseif($action_type == 'quiz-list')
                        @include('frontend.student.course.partial.render-quiz-list')
                    @else

                        @if($action_type == 'start-quiz')
                                @if(isset($answer))
                                <!-- Multiple Choice Quiz Start -->
                                <div class="multiple-quiz-block">

                                    <div class="course-watch-quiz-top-bar">
                                        <div class="course-watch-quiz-title d-flex justify-content-between align-items-center">
                                            <h5>{{$exam->name}}</h5>
                                        </div>

                                        <div class="quiz-answer-progress-bar d-flex justify-content-between">

                                            <div class="quiz-progress-left d-flex align-items-center">
                                                <p>Question {{$number_of_answer}} of {{$exam->questions->count()}}</p>
                                                <div class="barra">
                                                    <div class="barra-nivel" data-nivel="{{( ($number_of_answer * 100) / $exam->questions->count() ) }}%"></div>
                                                </div>
                                            </div>

                                            <div class="quiz-progress-right">
                                                @if(@$take_exam)
                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $expend_second =   $now->diffInSeconds($take_exam->created_at);
                                                    @endphp
                                                    <p>Time remaining: <span class="font-medium"> {{\Carbon\Carbon::parse($exam->duration * 60)->subSecond($expend_second)->format('H:i:s')}} of {{\Carbon\Carbon::parse($exam->duration * 60)->format('H:i:s')}}</span> </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(@$answer->question)
                                        <div class="multiple-quiz-answer-list-box">

                                        <p class="font-18 color-heading mb-15">{{$answer->question->name}}</p>

                                            @foreach($answer->question->options as $key => $option)
                                                @if($option->id == $answer->question_option_id)
                                                    <div class="form-check {{$answer->is_correct == 'yes' ? 'given-answer-right' : 'given-answer-wrong' }}  ">
                                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="0" required id="correct_ans11">
                                                        <label class="form-check-label mb-0 color-heading" for="correct_ans11">
                                                            {{$option->name}} <span class="iconify color-green correct-iconify" data-icon="akar-icons:check"></span><span class="iconify color-orange wrong-iconify" data-icon="radix-icons:cross-2"></span>
                                                        </label>
                                                    </div>
                                                @else
                                                    <div class="form-check {{$option->is_correct_answer == 'yes' ? 'correct-answer-was' : '' }} ">
                                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="0" required id="correct_ans11">
                                                        <label class="form-check-label mb-0 color-heading" for="correct_ans11">
                                                            {{$option->name}} <span class="iconify color-green correct-iconify" data-icon="akar-icons:check"></span><span class="iconify color-orange wrong-iconify" data-icon="radix-icons:cross-2"></span>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        <a href="{{route('student.my-course.show', [$course->slug, 'start-quiz', $exam->uuid])}}" class="quiz-answer-btn theme-btn theme-button1 default-hover-btn">Next</a>
                                    </div>
                                </div>
                                <!-- Multiple Choice Quiz End -->
                                @else
                                    <!-- Multiple Choice Quiz Start -->
                                    <div class="multiple-quiz-block">
                                        <form method="POST" action="{{route('student.save-exam-answer', [$course->uuid, $question->uuid, $take_exam->id])}}">
                                            @csrf
                                            <div class="course-watch-quiz-top-bar">
                                                <div class="course-watch-quiz-title d-flex justify-content-between align-items-center">
                                                    <h5>{{$exam->name}}</h5>
                                                </div>

                                                <div class="quiz-answer-progress-bar d-flex justify-content-between">

                                                    <div class="quiz-progress-left d-flex align-items-center">
                                                        <p>Question {{$number_of_answer + 1}} of {{$exam->questions->count()}}</p>
                                                        <div class="barra">
                                                            <div class="barra-nivel" data-nivel="{{( ($number_of_answer + 1) * 100 ) / $exam->questions->count() }}%"></div>
                                                        </div>
                                                    </div>

                                                    <div class="quiz-progress-right">
                                                        @if(@$take_exam)
                                                            @php
                                                                $now = \Carbon\Carbon::now();
                                                                $expend_second =   $now->diffInSeconds($take_exam->created_at);
                                                            @endphp
                                                            <p>Time remaining: <span class="font-medium"> {{\Carbon\Carbon::parse($exam->duration * 60)->subSecond($expend_second)->format('H:i:s')}} of {{\Carbon\Carbon::parse($exam->duration * 60)->format('H:i:s')}}</span> </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="multiple-quiz-answer-list-box">
                                                <p class="font-18 color-heading mb-15">{{$question->name}} - {{$question->id}}</p>
                                                @foreach($question->options as $key => $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"  name="selected_option_uuid" value="{{$option->uuid}}" required id="correct_ans_{{$key}}">
                                                        <label class="form-check-label mb-0 color-heading" for="correct_ans_{{$key}}">
                                                            {{$option->name}} <span class="iconify color-green correct-iconify" data-icon="akar-icons:check"></span><span class="iconify color-orange wrong-iconify" data-icon="radix-icons:cross-2"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="quiz-answer-btn theme-btn theme-button1 default-hover-btn">Give Answer</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Multiple Choice Quiz End -->
                                @endif
                        @endif

                        @if($action_type == 'quiz-result')
                                <!-- Multiple Choice Quiz Start -->
                                <div class="multiple-quiz-block">
                                    <div class="course-watch-quiz-top-bar">
                                        <div class="course-watch-quiz-title d-flex justify-content-between align-items-center">
                                            <h5>{{$exam->name}}</h5>
                                            <div class="course-watch-quiz-title-right-side d-flex">
                                                <p class="font-20 font-medium color-heading">Total Score: <span class="font-semi-bold">{{get_total_score($exam->id)}}</span></p>
                                                <p class="font-20 font-medium color-heading">Your Score: <span class="font-semi-bold">{{get_student_score($exam->id)}}</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($exam->questions as $question)
                                     <div class="multiple-quiz-answer-list-box">
                                        <p class="font-18 color-heading mb-15">{{$question->name}}</p>
                                         @foreach($question->options as $option)
                                            <div class="form-check {{get_answer_class($exam->id, $question->id, $option->id)}}">
                                                <input class="form-check-input" type="radio"  name="is_correct_answer" value="0" required id="correct_ans12">
                                                <label class="form-check-label mb-0 color-heading" for="correct_ans12">
                                                    {{$option->name}} <span class="iconify color-green correct-iconify" data-icon="akar-icons:check"></span><span class="iconify color-orange wrong-iconify" data-icon="radix-icons:cross-2"></span>
                                                </label>
                                            </div>
                                         @endforeach
                                    </div>
                                    @endforeach

                                    <div class="col-12">
                                        <div class="course-watch-quiz-btn-wrap">
                                            <a href="{{route('student.my-course.show', [$course->slug, 'quiz-list'])}}" class="quiz-answer-btn theme-btn theme-button3 quiz-back-btn default-hover-btn">Back</a>
                                            <a href="{{route('student.my-course.show', [$course->slug, 'leaderboard', $exam->uuid])}}" class="quiz-answer-btn theme-btn theme-button1 default-hover-btn">Next</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Multiple Choice Quiz End -->
                            @endif

                        @if($action_type == 'leaderboard')
                            <!-- Course Watch Quiz Leatherboard Area Start -->
                            <div class="go-back-btn mb-30">
                                <a href="{{route('student.my-course.show', [$course->slug, 'quiz-list'])}}" class="theme-btn quiz-back-btn theme-button1 default-hover-btn">Back to Quiz</a>
                            </div>
                            <div class="all-leatherboard-wrap">
                                <!--student-own Leatherboard start-->
                                <div class="course-watch-leatherboard-area student-own-leatherboard">
                                    <h6 class="leatherboard-heading d-flex justify-content-between text-white mb-20">
                                        <span>Your Position</span>
                                        <span>Quiz Mark</span>
                                        <span>Your Mark</span>
                                    </h6>
                                    <!-- Leatherboard Item Start -->
                                    <div class="leatherboard-item d-flex align-items-center justify-content-between">
                                        <div class="leatherboard-left d-flex align-items-center">
                                            <div class="student-position-no font-medium color-heading">{{get_position($exam->id)}}</div>
                                            <div class="student-img-wrap flex-shrink-0"><img src="{{getImageFile(auth::user()->image_path)}}" alt="img" class="img-fluid"></div>
                                            <div class="student-name font-medium color-heading">{{auth::user()->name}}</div>
                                        </div>
                                        <div class="leatherboard-right d-flex align-items-center justify-content-between">
                                            <div class="quiz-mark font-medium color-heading">{{get_total_score($exam->id)}}</div>
                                            <div class="your-quiz-mark font-medium color-heading">{{get_student_score($exam->id)}}</div>
                                        </div>
                                    </div>
                                    <!-- Leatherboard Item End -->

                                </div>
                                <!--student-own Leatherboard end-->

                                <!-- Merit List Leatherboard start-->
                                <div class="course-watch-leatherboard-area merit-list-leatherboard">
                                    <h6 class="leatherboard-heading d-flex justify-content-between text-white mb-20">
                                        <span>Leaderboard</span>
                                    </h6>
                                    <!-- Leatherboard Item Start -->
                                    @foreach($top5_take_exams as $key =>  $top5_take_exam)
                                        @if($top5_take_exam->user)
                                        <div class="leatherboard-item d-flex align-items-center justify-content-between">
                                            <div class="leatherboard-left d-flex align-items-center">
                                                <div class="student-position-no font-medium color-heading">{{$key + 1}}</div>
                                                <div class="student-img-wrap flex-shrink-0"><img src="{{ getImageFile($top5_take_exam->user->image_path) }}" alt="img" class="img-fluid"></div>
                                                <div class="student-name font-medium color-heading">{{$top5_take_exam->user->name}}</div>
                                                <div class="merit-list-crown-img-wrap flex-shrink-0 flex-shrink-0"><img src="{{ asset('frontend/assets/img/student-img/merit-crown.png') }}" alt="img" class="img-fluid"></div>
                                            </div>
                                            <div class="leatherboard-right d-flex align-items-center justify-content-between">
                                                <div class="quiz-mark font-medium color-heading">{{get_total_score($exam->id)}}</div>
                                                <div class="your-quiz-mark font-medium color-heading">{{get_student_by_student_score($exam->id, $top5_take_exam->user_id)}}</div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- Merit List Leatherboard end-->

                                <!--Leatherboard for all student Start-->
                                @if($take_exams->count() > 0)
                                    <div class="course-watch-leatherboard-area">
                                    @foreach($take_exams as $k =>  $take_exam)
                                        @if($take_exam->user)
                                        <div class="leatherboard-item d-flex align-items-center justify-content-between">
                                            <div class="leatherboard-left d-flex align-items-center">
                                                <div class="student-position-no font-medium color-heading">{{$k + 1}}</div>
                                                <div class="student-img-wrap flex-shrink-0"><img src="{{ getImageFile($take_exam->user->image_path) }}" alt="img" class="img-fluid"></div>
                                                <div class="student-name font-medium color-heading">{{$take_exam->user->name}}</div>
                                            </div>
                                            <div class="leatherboard-right d-flex align-items-center justify-content-between">
                                                <div class="quiz-mark font-medium color-heading">{{get_total_score($exam->id)}}</div>
                                                <div class="your-quiz-mark font-medium color-heading">{{get_student_by_student_score($exam->id, $top5_take_exam->user_id)}}</div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                                <!--Leatherboard for all student End-->
                            </div>
                            <!-- Quiz Leatherboard Area End -->
                        @endif


                    @endif
                @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{ __('Empty Quiz') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                @endif

            </div>
        </div>
    </div>
</div>
