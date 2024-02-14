@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">{{ __(@$title) }}</h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$title) }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-live-class-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __(@$title) }}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    @if (count($followers) > 0)
                        <div class="table-responsive table-responsive-xl">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>
                                        <th scope="col">{{ __('Image') }}</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($followers as $follower)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="table-data-img-wrap"><img src="{{ asset($follower->image) }}"
                                                        alt="img" class="img-fluid"></div>
                                            </td>
                                            <td>
                                                @if ($follower->role == USER_ROLE_INSTRUCTOR)
                                                    <a href="{{ route('userProfile', $follower->instructor->user_id) }}"
                                                        target="_blank">{{ $follower->name }}</a>
                                                @else
                                                    {{ $follower->name }}
                                                @endif
                                            </td>
                                            <td>{{ date('Y-m-d', strtotime($follower->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- If there is no data Show Empty Design Start -->
                        <div class="empty-data">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img"
                                class="img-fluid">
                            <h5 class="my-3">{{ __('Empty Followers') }}</h5>
                        </div>
                        <!-- If there is no data Show Empty Design End -->
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
