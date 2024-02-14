<div class="tab-pane fade show active" id="instructor-tab-pane" role="tabpanel" aria-labelledby="instructor-tab"
    tabindex="0">
    <div class="nav nav-pills justify-content-center align-items-center" id="instructorpills-tab" role="tablist">
        <span class="plan-switch-month-year-text mx-3"> {{ __('Monthly') }}</span>
        <div class="price-tab-lang">

            <span class="nav-item" role="presentation">

                <button class="nav-link active" id="pills-monthly-2-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-monthly-2" type="button" role="tab" aria-controls="pills-monthly-2"
                    aria-selected="true"></button>
            </span>
            <span class="nav-item" role="presentation">

                <button class="nav-link" id="pills-yearly-2-tab" data-bs-toggle="pill" data-bs-target="#pills-yearly-2"
                    type="button" role="tab" aria-controls="pills-yearly-2" aria-selected="false"></button>
            </span>
        </div>
        <span class="plan-switch-month-year-text mx-3">
            {{ __('Yearly') }}
        </span>

    </div>
    <div class="tab-content" id="instructorpillsContent">
        <div class="tab-pane fade show active overflow-x-scroll" id="pills-monthly-2" role="tabpanel"
            aria-labelledby="pills-monthly-2-tab">
            <table class="table text-center theme-border bg-light mt-56 position-relative mb-136 price-table">
                <thead>
                    <tr class="price-title-bg">
                        <th scope="col">
                            <h6 class="our-plan">{{ __('Saas Plan') }}</h6>
                        </th>

                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <th scope="col">
                                    <div class="price-box-title">
                                        <img src="{{ getImageFile($saas->icon) }}" alt="{{ $saas->title }}">
                                        <h5 class="price-labe">{{ $saas->title }}</h5>
                                    </div>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>

                <tbody class="price-body-info">
                    <tr class="price-title-body">
                        <th scope="row" class="sf-min-w-364">
                            <p class="title-price-list">{{ __('Price') }} / {{ __('Month') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    <div class="price-sub-title-part">
                                        @if ($saas->discounted_monthly_price < 1)
                                            <h4 class="price-sub-title">{{ __('Full Free') }}</h6>
                                            @else
                                                <h4 class="price-sub-title">
                                                    {{ $saas->discounted_monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                                </h4>
                                                @if ($saas->discounted_monthly_price != $saas->monthly_price)
                                                    <p class="price-currency text-decoration-line-through">
                                                        {{ $saas->monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                                    </p>
                                                @endif
                                        @endif
                                    </div>
                                </td>
                            @endif
                        @endforeach

                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Course Access') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    @if ($saas->course > 0)
                                        <p class="title-price-list">{{ $saas->course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Bundle Courses') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    @if ($saas->bundle_course > 0)
                                        <p class="title-price-list">{{ $saas->bundle_course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Subscription Courses') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    @if ($saas->subscription_course > 0)
                                        <p class="title-price-list">{{ $saas->subscription_course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Hours of Consultancy') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    @if ($saas->consultancy > 0)
                                        <p class="title-price-list">{{ $saas->consultancy }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Minimum Sale commission') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    <p class="title-price-list">{{ $saas->admin_commission }}%</p>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-btu-line">
                        <td>

                        </td>
                        @php
                            $matched = $instructorSaas->where('id', @$mySaasPackage->package_id)->first();
                            $disabledYearly = !is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false;
                            $disabledMonthly = $disabledYearly ? true : (!is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
                        @endphp
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                <td>
                                    <form method="post"
                                        action="{{ route('student.subscription.checkout', $saas->uuid) }}">
                                        @csrf
                                        <input type="hidden" name="monthly" value=1>
                                        <button type="submit" {{ $disabledMonthly ? 'disabled' : '' }}
                                            class="{{ $disabledMonthly ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1">{{ $disabledMonthly &&
                                            ($saas->id == @$mySaasPackage->package_id && @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY) ==
                                                SUBSCRIPTION_TYPE_MONTHLY
                                                ? __('Current Plan')
                                                : __('Get Started') }}</button>
                                    </form>
                                </td>
                                @php
                                    if ($disabledMonthly && $saas->id == $matched->id) {
                                        $disabledMonthly = false;
                                    }
                                @endphp
                            @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade overflow-x-scroll" id="pills-yearly-2" role="tabpanel"
            aria-labelledby="pills-yearly-2-tab">
            <table class="table text-center theme-border bg-light mt-56 position-relative mb-136 price-table">
                <thead>
                    <tr class="price-title-bg">
                        <th scope="col">
                            <h6 class="our-plan">{{ __('Saas Plan') }}</h6>
                        </th>

                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <th scope="col">
                                    <div class="price-box-title">
                                        <img src="{{ getImageFile($saas->icon) }}" alt="{{ $saas->title }}">
                                        <h5 class="price-labe">{{ $saas->title }}</h5>
                                    </div>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>

                <tbody class="price-body-info">
                    <tr class="price-title-body">
                        <th scope="row" class="sf-min-w-364">
                            <p class="title-price-list">{{ __('Price') }} / {{ __('Yearly') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    <div class="price-sub-title-part">
                                        @if ($saas->discounted_yearly_price < 1)
                                            <h4 class="price-sub-title">{{ __('Full Free') }}</h6>
                                            @else
                                                <h4 class="price-sub-title">
                                                    {{ $saas->discounted_yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                                </h4>
                                                @if ($saas->discounted_yearly_price != $saas->yearly_price)
                                                    <p class="price-currency text-decoration-line-through">
                                                        {{ $saas->yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                                    </p>
                                                @endif
                                        @endif
                                    </div>
                                </td>
                            @endif
                        @endforeach

                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Course Access') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    @if ($saas->course > 0)
                                        <p class="title-price-list">{{ $saas->course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Bundle Courses') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    @if ($saas->bundle_course > 0)
                                        <p class="title-price-list">{{ $saas->bundle_course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Create Subscription Courses') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    @if ($saas->subscription_course > 0)
                                        <p class="title-price-list">{{ $saas->subscription_course }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Hours of Consultancy') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    @if ($saas->consultancy > 0)
                                        <p class="title-price-list">{{ $saas->consultancy }}</p>
                                    @else
                                        <p class="title-price-list"><i class="fas fa-times"></i></p>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-body-list">
                        <th scope="row">
                            <p class="title-price-list">{{ __('Minimum Sale commission') }}</p>
                        </th>
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    <p class="title-price-list">{{ $saas->admin_commission }}%</p>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="price-btu-line">
                        <td>

                        </td>
                        @php
                            $matched = $instructorSaas->where('id', @$mySaasPackage->package_id)->first();
                            $disabledYearly = !is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false;
                        @endphp
                        @foreach ($instructorSaas as $index => $saas)
                            @if (!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                <td>
                                    <form method="post"
                                        action="{{ route('student.subscription.checkout', $saas->uuid) }}">
                                        @csrf
                                        <input type="hidden" name="monthly" value=0>
                                        <button type="submit" {{ $disabledYearly ? 'disabled' : '' }}
                                            class="{{ $disabledYearly ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1">{{ $disabledYearly &&
                                            $saas->id == @$mySaasPackage->package_id &&
                                            @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY
                                                ? __('Current Plan')
                                                : __('Get Started') }}</button>
                                    </form>
                                </td>
                                @php
                                    if ($disabledYearly && $saas->id == $matched->id) {
                                        $disabledYearly = false;
                                    }
                                @endphp
                            @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
