@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">{{ __('Withdraw History') }}</h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Withdraw History') }}</li>
            </ol>
        </nav>
        <!-- Breadcrumb End-->
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-withdraw-history-box bg-white">

            <h6 class="instructor-info-box-title">{{ __('Transactions') }}</h6>
            @if(count($withdraws))
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('ID') }}</th>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('Date') }}</th>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('Amount') }}</th>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('Method') }}</th>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('Status') }}</th>
                        <th scope="col" class="color-gray font-14 font-medium">{{ __('Receipt') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($withdraws as $withdraw)
                        <tr>
                            <td>#{{$withdraw->transection_id}}</td>
                            <td>{{$withdraw->created_at->format("d M Y")}}</td>
                            <td>
                                @if(get_currency_placement() == 'after')
                                    {{$withdraw->amount}} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{$withdraw->amount}}
                                @endif
                            </td>
                            <td>
                                {!! getBeneficiaryDetails($withdraw->beneficiary) !!}
                            </td>
                            <td class="{{$withdraw->status == 1 ? 'color-green' :  'text-danger'}}">
                              <div>
                                  @if($withdraw->status == 1)
                                      {{ __('Complete') }}
                                  @endif
                                  @if($withdraw->status == 2)
                                      {{ __('Rejected') }}
                                  @endif
                                  @if($withdraw->status == '0')
                                      {{ __('Pending') }}
                                  @endif
                              </div>
                                <div class="text-12 text-muted">
                                   {{\Illuminate\Support\Str::words($withdraw->note, 4)}}
                                </div>
                            </td>
                            <td>
                                @if($withdraw->status == 1)
                                <a href="{{route('finance.download-receipt', [$withdraw->uuid])}}">
                                    <span class="iconify" data-icon="bx:bxs-file-pdf"></span>
                                </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$withdraws->links()}}
            </div>
            @else
                <!-- If there is no data Show Empty Design Start -->
                <div class="empty-data">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="my-3">{{ __('Empty Transactions') }}</h5>
                </div>
                <!-- If there is no data Show Empty Design End -->
            @endif
        </div>

    </div>
@endsection

