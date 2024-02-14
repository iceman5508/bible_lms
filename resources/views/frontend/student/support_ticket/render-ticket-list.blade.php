@if(count($tickets))
<div class="table-responsive table-responsive-xl">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">{{ __('Ticket Number') }}</th>
            <th scope="col">{{ __('Subject') }}</th>
            <th scope="col">{{ __('Last Response') }}</th>
            <th scope="col">{{ __('Priority') }}</th>
            <th scope="col">{{ __('Status') }}</th>
            <th scope="col">{{ __('Action') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ date('Y-m-d',strtotime($ticket->updated_at)) }}</td>
                <td>{{ @$ticket->priority->name }}</td>
                <td>
                    @if($ticket->status == 1) {{ __('Open') }} @endif
                    @if($ticket->status == 2) <div class="color-orange">{{ __('Closed') }}</div> @endif
                </td>
                <td>
                    <a href="{{ route('student.support-ticket.show', $ticket->uuid) }}" class="theme-btn theme-button1 green-theme-btn default-hover-btn">
                        {{ __('View Details') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Start -->
@if(@$tickets->hasPages())
    {{ @$tickets->links('frontend.paginate.paginate') }}
@endif
<!-- Pagination End -->
@else
    <div class="no-course-found text-center">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h5 class="mt-3">{{ __('Empty Ticket') }}</h5>
    </div>
@endif
