
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('files/favicon.png') }}" type="image/x-icon">
    <title>Update Your Application </title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zaifiles/assets/style.css') }}">
</head>
<body>
@yield('preloader')

<div class="overlay-wrap">
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="breadcrumb-text">
                        <a class="brand-logo" href="#"><img src="{{ getImageFile(get_option('app_logo')) }}" alt="logo"></a>
                        <h2>LMSZAI - Learning Management System</h2>
                        <p>{{ \Carbon\Carbon::parse(now())->format('l, j F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pre-installation-area">
        <div class="container">
            <div class="section-wrap">
                <div class="section-wrap-header">
                    <div class="progres-stype">
                        @if(config('app.build_version') == get_option('app_version'))
                            <p class="me-2 my-2"><span class="text-danger">*</span>Your application is upto date</p>
                        @else
                        <form action="{{ route('process-update') }}" method="POST">
                            @csrf
                            <p class="me-2 mb-2"><span class="text-danger">*</span> New version {{ config('app.current_version') }} </p>
                            <p class="me-2 mb-2"><span class="text-danger">*</span> Current version {{ get_option('current_version', '2.4') }} </p>
                            <p class="me-2 mb-2"><span class="text-danger">*</span> Download your database and present script to avoid any errors. (Safety first) </p>
                            <p class="me-2 mb-2"><span class="text-danger">*</span> Please click Update now button, may its need sometime</p>
                            <div class="mt-3">
                                <div class="single-section">
                                    <h4 class="section-title mb-2">Please enter your Item purchase code and customer email</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Customer E-mail</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="example@example.com" />
                                            </div>
                                            @if($errors->has('email'))
                                                <div class="error text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="purchase_code">Item purchase code</label>
                                                <input type="text" class="form-control" id="purchase_code" name="purchase_code" value="{{ old('purchase_code') }}" placeholder="31200164-dd02-49ea-baef-3865c90acc123" />
                                            </div>
                                            @if($errors->has('purchase_code'))
                                                <div class="error text-danger">{{ $errors->first('purchase_code') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button class="primary-btn next" id="submitNext" type="submit">Update Now</button>
                            </div>
                        </form>

                        @endif
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

@stack('script')
</body>
</html>
