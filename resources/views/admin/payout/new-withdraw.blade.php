@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{__('Withdraw Request')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Withdraw Request')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{__('Withdraw Request')}}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Transaction ID')}}</th>
                                    <th>{{__('User')}}</th>
                                    <th>{{__('Payment Method')}}</th>
                                    <th>{{__('Request Date')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($withdraws as $withdraw)
                                    @if($withdraw->user)
                                        <tr class="removable-item">
                                        <td>{{$withdraw->transection_id}}</td>
                                        <td>
                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{__('Name')}}</span>: {{@$withdraw->user->student->name ?? @$withdraw->user->instructor->name}}
                                            </div>
                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{__('Email')}}</span>: {{@$withdraw->user->email}}
                                            </div>
                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{__('Phone')}}</span>: {{@$withdraw->user->student->phone_number ?? @$withdraw->user->instructor->phone_number}}
                                            </div>
                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{__('User Type')}}</span>:
                                                @if(@$withdraw->user->role == 2 && @$withdraw->user->is_affiliator == 1)
                                                    {{ __('Instructor & Affiliator') }}
                                                @elseif(@$withdraw->user->role == 3 && @$withdraw->user->is_affiliator == 1)
                                                    {{ __('Student & Affiliator') }}
                                                @elseif(@$withdraw->user->role == 2)
                                                    {{ __('Instructor') }}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {!! getBeneficiaryDetails($withdraw->beneficiary) !!}
                                        </td>
                                        <td>
                                            {{$withdraw->created_at->format(get_option('app_date_format'))}}
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$withdraw->amount}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$withdraw->amount}}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="javascript:void(0);" data-uuid="{{$withdraw->uuid}}" data-status="2" class="btn-action hold-btn mr-30 note" title="Make as Reject">
                                                    {{__('Reject')}}</span>
                                                </a>
                                                <a href="javascript:void(0);" data-uuid="{{$withdraw->uuid}}" data-status="1" class="btn-action approve-btn note" title="Make as Complete">
                                                    {{__('Complete')}}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                        {{$withdraws->links()}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->

    <!--Withdrawal Modal Start-->
    <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" >
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h4>Write Note</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('payout.change-withdraw-status')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="uuid" id="uuid">
                        <input type="hidden" name="status" id="status">
                        <div class="custom-form-group mb-3 row">
                            <div class="col-lg-12">
                                <textarea name="note" id="note" required class="form-control" placeholder="{{__('Note')}}"></textarea>
                            </div>
                        </div>

                        <div class="custom-form-group mb-3 row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-blue mr-30">{{__('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Withdrawal Modal End-->

@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
    <script src="{{asset('admin/js/custom/withdraw.js')}}"></script>
@endpush
