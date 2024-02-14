@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Ranking Badge') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Ranking Badge') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-ranking-badge-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Ranking Badge List') }}</h6>
                <a href="{{ route('organization.dashboard') }}" class="theme-btn theme-button1 default-back-btn default-hover-btn m-0">{{ __('Back') }}</a>
            </div>

            <div class="row ranking-badge-page-row">
                <div class="col-lg-12 col-xl-12 ranking-badge-page-side-box">
                    <div class="ranking-items-wrap">
                        @foreach(@$levels as $level)
                        <div class="ranking-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center ranking-item-left">
                                <div class="flex-shrink-0 user-img-wrap">
                                    <img src="{{ getImageFile($level->image_path) }}" alt="img" class="img-fluid">
                                </div>
                                <div class="flex-grow-1 ranking-content-in-right">
                                    <h6 class="font-15">{{ $level->name }}</h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center ranking-item-right">
                                <div class="flex-shrink-0">
                                    <p class="font-15 color-heading text-center">{{ __('Earning') }}</p>
                                    <p class="font-15 color-heading text-center">
                                        @if(get_currency_placement() == 'after')
                                            {{ $level->earning }} {{ get_currency_symbol() }}
                                        @else
                                            {{ get_currency_symbol() }} {{ $level->earning }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ranking-content-in-right">
                                    <p class="font-15 color-heading text-center">{{ __('Student') }}</p>
                                    <p class="font-15 color-heading text-center">{{ $level->student }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
