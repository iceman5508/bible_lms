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
                            <h3 class="page-banner-heading color-heading pb-15">{{__('List of all device from which you have logged in')}}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{__('My login devices')}}</li>
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

<!-- Wishlist Page Area Start -->
<section class="wishlist-page-area my-courses-page">
    <div class="container">
        <div class="row">
            <!-- Courses Filter Bar Start-->
            <div class="col-12">
                <div class="courses-filter-bar d-flex align-items-start justify-content-between align-items-end">
                    <div class="filter-bar-left">
                        <a href="#" class="theme-btn theme-button1 theme-button3" onclick="event.preventDefault(); document.getElementById('logout-device').submit();">
                            {{__('Logout from all devices')}}
                        </a>
                        <form id="logout-device" action="{{ route('student.logout_device') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>

                    <div class="filter-bar-right">
                        <p>{{ count($devices).'/'.$limit.' '.__('device') }}</p>
                    </div>
                </div>
            </div>
            <!-- Courses Filter Bar End-->

            <div>
                @include('frontend.login_device_list')
            </div>
        </div>
    </div>
</section>
<!-- Wishlist Page Area End -->

</div>
@endsection
