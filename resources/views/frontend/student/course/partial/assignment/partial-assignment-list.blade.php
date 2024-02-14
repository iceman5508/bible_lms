<div class="course-watch-quiz-list-table course-watch-assignment-list-table ">
    @if(count($assignments))
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">{{ __('Assignment Topic') }}</th>
                    <th scope="col">{{ __('Marks') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assignments as $assignment)
                    <tr>
                        <td>{{ Str::limit($assignment->name, 50) }}</td>
                        <td>{{ $assignment->marks }}</td>
                        <td>
                            <div class="notice-board-action-btns">
                                <button class="theme-btn theme-button1 light-purple-theme-btn default-hover-btn viewAssignmentDetails"
                                        data-route="{{ route('student.assignment-details') }}" data-assignment_id="{{ $assignment->id }}">
                                    {{ __('View Details') }}
                                </button>
                                <button class="theme-btn theme-button1 green-theme-btn default-hover-btn viewAssignmentResult"
                                        data-route="{{ route('student.assignment-result') }}" data-assignment_id="{{ $assignment->id }}">
                                    {{ __('See Result') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    @else
        <!-- If there is no data Show Empty Design Start -->
        <div class="empty-data">
            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
            <h5 class="my-3">{{ __('Empty Assignment') }}</h5>
        </div>
        <!-- If there is no data Show Empty Design End -->
    @endif
</div>
