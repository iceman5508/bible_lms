@extends('frontend.layouts.app')

@section('content')
<div class="bg-page">
    <!-- Page Header Start -->
    <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="blank-page-banner-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading color-heading pb-15"> {{__('My Beneficiary')}} </h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a
                                                href="{{route('affiliate.dashboard')}}">{{__('Dashboard')}}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{__('My
                                            Beneficiary')}}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Page Header End -->
    <section class="wishlist-page-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="affiliator-dashboard-wrap bg-white">
                        <div class="row affiliator-dashboard-title align-items-center border-bottom mb-30 pb-20 mx-0">
                            <div class="col-md-12 col-lg-5 col-xl-5 px-0">
                                <h5>{{ __('Beneficiary For Withdraw') }}</h5>
                            </div>
                            <div class="col-md-12 col-lg-7 col-xl-7 px-0">
                                <div class="affiliate-top-title-btns text-end">
                                    <button type="button" class="theme-btn theme-button1 default-hover-btn"
                                        data-bs-toggle="modal" data-bs-target="#beneficiaryModal">
                                        {{ __('Add Beneficiary') }}
                                    </button>
                                    <a href=" {{ route('wallet./') }}"
                                        class="theme-btn theme-button1 green-theme-btn default-hover-btn">GoTo
                                        Wallet</a>
                                </div>
                            </div>
                        </div>

                        <div class="instructor-profile-right-part">
                            <div class="instructor-my-cards-box instructor-payment-settings-page bg-white">
                                <div class="row">
                                    <!-- instructor my-cards form item start -->
                                    <div class="col-12 col-sm-12">
                                        <div class="customers__table">
                                            <table id="customers-table" class="table booking-history-past-table">
                                                <thead>
                                                    <tr>
                                                        <th width="25%">{{ __('Name') }}</th>
                                                        <th width="25%">{{ __('Type') }}</th>
                                                        <th width="40%">{{ __('Data') }}</th>
                                                        <th width="10%">{{__('Action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($beneficiaries as $beneficiary)
                                                    <tr>
                                                        <td>
                                                            {{$beneficiary->beneficiary_name}}
                                                        </td>
                                                        <td>
                                                            {{getBeneficiaryName($beneficiary->type)}}
                                                        </td>
                                                        <td>
                                                            {!! getBeneficiaryDetails($beneficiary) !!}
                                                        </td>
                                                        <td>
                                                            <div class="action__buttons">
                                                                <form action="{{ route('wallet.beneficiary_status.change', $beneficiary->uuid) }}" method="post">
                                                                    <select class="status form-select">
                                                                        <option value="{{ PACKAGE_STATUS_ACTIVE }}" {{ ($beneficiary->status == PACKAGE_STATUS_ACTIVE) ? 'selected' : '' }}>{{ getPackageStatus(PACKAGE_STATUS_ACTIVE) }}</option>
                                                                        <option value="{{ PACKAGE_STATUS_DISABLED }}" {{ ($beneficiary->status == PACKAGE_STATUS_DISABLED) ? 'selected' : '' }}>{{ getPackageStatus(PACKAGE_STATUS_DISABLED) }}</option>
                                                                    </select>
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--Withdrawal Modal Start-->
<div class="modal fade" id="beneficiaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="font-medium">{{ __('Add Beneficiary') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="beneficiaryForm" action="{{route('wallet.save.my-beneficiary')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-30">
                            <label class="font-medium font-15 color-heading">{{ __('Beneficiary Name') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="beneficiary_name"
                                placeholder="{{ __('Beneficiary Name') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-30">
                            <label class="font-medium font-15 color-heading">{{ __('Type') }}<span
                                    class="text-danger">*</span></label>
                            <select name="type" class="form-select">
                                <option value="{{ BENEFICIARY_CARD }}">{{ getBeneficiaryName(BENEFICIARY_CARD) }}
                                </option>
                                <option value="{{ BENEFICIARY_BANK }}">{{ getBeneficiaryName(BENEFICIARY_BANK) }}
                                </option>
                                <option value="{{ BENEFICIARY_PAYPAL }}">{{ getBeneficiaryName(BENEFICIARY_PAYPAL) }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="benificary-type-block" id="beneficiary-{{ BENEFICIARY_CARD }}">
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Card
                                    number') }}<span class="text-danger">*</span></label>
                                <input type="text" name="card_number" class="form-control"
                                    placeholder="{{ __('1245 2154 2154 215') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Card
                                    Holder Name') }}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="card_holder_name"
                                    placeholder="{{ __('Your name') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Month')
                                    }}<span class="text-danger">*</span></label>
                                <select name="expire_month" class="form-select">
                                    <option value="">{{ __('Select Month') }}</option>
                                    <option value="1">{{
                                        __('January') }}</option>
                                    <option value="2">{{
                                        __('February') }}</option>
                                    <option value="3">{{
                                        __('March') }}</option>
                                    <option value="4">{{
                                        __('April') }}</option>
                                    <option value="5">{{
                                        __('May') }}</option>
                                    <option value="6">{{
                                        __('June') }}</option>
                                    <option value="7">{{
                                        __('July') }}</option>
                                    <option value="8">{{
                                        __('August') }}</option>
                                    <option value="9">{{
                                        __('September') }}</option>
                                    <option value="10">{{
                                        __('October') }}</option>
                                    <option value="11">{{
                                        __('November') }}</option>
                                    <option value="12">{{
                                        __('December') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Year')
                                    }}<span class="text-danger">*</span></label>
                                <select name="expire_year" class="form-select">
                                    <option value="">{{ __('Select Year') }}</option>
                                    @for($year = \Carbon\Carbon::now()->format('Y'); $year < \Carbon\Carbon::now()->
                                        addYear(20)->format('Y');
                                        $year++)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="benificary-type-block d-none" id="beneficiary-{{ BENEFICIARY_BANK }}">
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Bank Name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control"
                                    placeholder="{{ __('EX. Switch Bank') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Account Name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_account_name"
                                    placeholder="{{ __('Mr. XYZ') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Account Number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_account_number"
                                    placeholder="{{ __('0000000000') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Routing Number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_routing_number"
                                    placeholder="{{ __('Ex. 546484') }}">
                            </div>
                        </div>
                    </div>

                    <div class="benificary-type-block d-none" id="beneficiary-{{ BENEFICIARY_PAYPAL }}">
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="font-medium font-15 color-heading">{{ __('Paypal
                                    Email') }}<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="paypal_email" placeholder="EX. example@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{
                                __('Add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Withdrawal Modal End-->

@endsection

@push('script')
<script>

    $('#customers-table').DataTable({
        language: {
            'paginate': {
                'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
            }
        },
    });

    $(document).on('change', '.status', function(){
        $(this).closest('form').submit();
    });

    $(document).on('change', ':input[name=type]', function(){
        let val = $(this).val();
        $(document).find('.benificary-type-block').addClass('d-none');
        $(document).find('#beneficiary-'+val).removeClass('d-none');
    });

    $(document).on('submit', '#beneficiaryForm', function(e){
        e.preventDefault();

        var form = $('#beneficiaryForm');

        $.ajax({
            type: 'POST',
            url: "{{ route('wallet.save.my-beneficiary') }}",
            data: $(form).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.success){
                    toastr.success('', response.message);
                    $('#beneficiaryModal').modal('toggle');
                }
            },
            error: function(failData){
                if(typeof failData.responseJSON.errors != 'undefined'){
                    $.each(failData.responseJSON.errors, function(ind, val){
                        toastr.error('', val[0]);
                    });
                }
            }
        });
    });
</script>
@endpush
