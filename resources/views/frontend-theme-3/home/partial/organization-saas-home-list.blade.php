<div class="tab-pane fade" id="organization-tab-pane" role="tabpanel" aria-labelledby="organization-tab" tabindex="0">
    <div class="nav nav-pills justify-content-center align-items-center" role="tablist">
        <span class="plan-switch-month-year-text mx-3"> {{ __('Monthly') }}</span>
        <div class="price-tab-lang">

            <span class="nav-item" role="presentation">

                <button class="nav-link active" id="pills-organization-monthly-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-organization-monthly" type="button" role="tab"
                    aria-controls="pills-organization-monthly" aria-selected="true"></button>
            </span>
            <span class="nav-item" role="presentation">

                <button class="nav-link" id="pills-organization-yearly-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-organization-yearly" type="button" role="tab"
                    aria-controls="pills-organization-yearly" aria-selected="false"></button>
            </span>
        </div>
        <span class="plan-switch-month-year-text mx-3">
            {{ __('Yearly') }}
        </span>

    </div>
    <div class="tab-content mt-56" id="">
        @php
        $matched = $organizationSaas->where('id', @$mySubscriptionPackage->package_id)->first();
        $disabledYearly = (!is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ?
        true : false);
        $disabledMonthly = ($disabledYearly) ? true : (!is_null($matched) && $mySubscriptionPackage->subscription_type
        == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
        @endphp
        <div class="tab-pane fade show active overflow-x-scroll" id="pills-organization-monthly" role="tabpanel"
            aria-labelledby="pills-organization-monthly-tab">

            <div class="row">
                <div class="col-lg-7 mb-4  order-lg-first">

                    <div class="tab-content">
                        @php
                        $first = 0;
                        @endphp
                        @foreach ($organizationSaas as $index => $saas)
                        @if (!(get_option('saas_org_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                            <div class="tab-pane fade {{ ++$first == 1 ? 'show active' : '' }}"
                            id="pills-organization-monthly-{{ $saas->id }}" role="tabpanel"
                            aria-labelledby="pills-organization-monthly-{{ $saas->id }}-tab">
                            <div class="price-list-content">
                                <ul>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __('Unlimited create of ').' '.
                                            $saas->instructor. ' '.__('instructor') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __('Create ').' '. $saas->student. '
                                            '.__('student') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __('Unlimited Create ').' '.
                                            $saas->course. ' '.__('Course') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __("Create") .' '. $saas->bundle_course.'
                                            '.__('Bundle Course') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __("Enable") .' '.
                                            $saas->subscription_course.' '.__('Subscription Course') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __("Give") .' '. $saas->consultancy.'
                                            '.__('hour consultancy') }}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                    <li class="align-items-center d-flex justify-content-between">
                                        <p class="section-sub-heading m-0">{{ __("Minimum of") .' '.
                                            $saas->admin_commission .'% '.__('sale commission')}}</p>
                                        <span class="iconify" data-icon="bi:check-lg"></span>
                                    </li>
                                </ul>
                                <form method="post" action="{{ route('student.subscription.checkout', $saas->uuid) }}">
                                    @csrf
                                    <input type="hidden" name="monthly" value=1>
                                    <button type="submit" {{ ($disabledMonthly) ? 'disabled' : '' }}
                                        class="{{ ($disabledMonthly) ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1 mt-25">{{
                                        ($disabledMonthly && ($saas->id == @$mySaasPackage->package_id &&
                                        @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY) ==
                                        SUBSCRIPTION_TYPE_MONTHLY) ? __("Current Plan") : __("Get Started") }}</button>
                                </form>
                            </div>
                    </div>
                    @endif
                    @php
                    if ($disabledMonthly && $saas->id == $matched->id) {
                    $disabledMonthly = false;
                    }
                    @endphp
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5 mb-4 order-first order-lg-last">
                <div class="price-landing-page-tab">
                    <ul class="sf-sass-plan nav-pills" role="tablist">
                        @php
                        $first = 0;
                        @endphp
                        @foreach ($organizationSaas as $index => $saas)
                        @if (!(get_option('saas_org_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                            <li class="nav-item" role="presentation">
                            <div class="nav-link {{ ++$first == 1 ? 'active' : '' }}"
                                id="pills-organization-monthly-{{ $saas->id }}-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-organization-monthly-{{ $saas->id }}" type="button" role="tab"
                                aria-controls="pills-organization-monthly-{{ $saas->id }}"
                                aria-selected="{{ $first == 1 ? 'true' : 'false' }}">
                                <div class="landing-tab-price">
                                    <div class="price-label-logo">
                                        <img src="{{ getImageFile($saas->icon) }}" alt="{{ $saas->title }}"
                                            class=" rounded-circle">
                                        <h6>{{ $saas->title }}</h6>
                                    </div>

                                    <div class="price-label">
                                        @if ($saas->discounted_monthly_price < 1) <h4 class="price-sub-title">{{
                                            __('Full Free') }}</h6>
                                            @else
                                            <h4 class="price-sub-title">
                                                {{ $saas->discounted_monthly_price . ($currencySymbol ??
                                                get_currency_symbol()) }}/ {{ __('Month') }}
                                            </h4>
                                            @if ($saas->discounted_monthly_price != $saas->monthly_price)
                                            <p class="price-currency text-decoration-line-through">
                                                {{ $saas->monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                            </p>
                                            @endif
                                            @endif
                                    </div>
                                </div>
                            </div>
                            </li>
                            @endif
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade overflow-x-scroll" id="pills-organization-yearly" role="tabpanel"
        aria-labelledby="pills-organization-yearly-tab">
        <div class="row">
            <div class="col-lg-7 mb-4 order-lg-first">

                <div class="tab-content">
                    @php
                    $first = 0;
                    @endphp
                    @foreach ($organizationSaas as $index => $saas)
                    @if (!(get_option('saas_org_default_package_type') == 'monthly' && $saas->yearly_price <= 1)) <div
                        class="tab-pane fade {{ ++$first == 1 ? 'show active' : '' }}"
                        id="pills-organization-yearly-{{ $saas->id }}" role="tabpanel"
                        aria-labelledby="pills-organization-yearly-{{ $saas->id }}-tab">
                        <div class="price-list-content">
                            <ul>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __('Unlimited create of ').' '.
                                        $saas->instructor. ' '.__('instructor') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __('Create ').' '. $saas->student. '
                                        '.__('student') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __('Unlimited Create ').' '. $saas->course. '
                                        '.__('Course') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __("Create") .' '. $saas->bundle_course.'
                                        '.__('Bundle Course') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __("Enable") .' '.
                                        $saas->subscription_course.' '.__('Subscription Course') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __("Give") .' '. $saas->consultancy.'
                                        '.__('hour consultancy') }}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                                <li class="align-items-center d-flex justify-content-between">
                                    <p class="section-sub-heading m-0">{{ __("Minimum of") .' '. $saas->admin_commission
                                        .'% '.__('sale commission')}}</p>
                                    <span class="iconify" data-icon="bi:check-lg"></span>
                                </li>
                            </ul>
                            <form method="post" action="{{ route('student.subscription.checkout', $saas->uuid) }}">
                                @csrf
                                <input type="hidden" name="monthly" value=0>
                                <button type="submit" {{ ($disabledYearly) ? 'disabled' : '' }}
                                    class="{{ ($disabledYearly) ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1 mt-25">{{
                                    ($disabledYearly && ($saas->id == @$mySaasPackage->package_id &&
                                    @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY) ==
                                    SUBSCRIPTION_TYPE_YEARLY) ? __("Current Plan") : __("Get Started") }}</button>
                            </form>
                        </div>
                </div>
                @endif
                @php
                if ($disabledYearly && $saas->id == $matched->id) {
                $disabledYearly = false;
                }
                @endphp
                @endforeach
            </div>
        </div>
        <div class="col-lg-5 mb-4 order-first order-lg-last">
            <div class="price-landing-page-tab">
                <ul class="sf-sass-plan nav-pills" role="tablist">
                    @php
                    $first = 0;
                    @endphp
                    @foreach ($organizationSaas as $index => $saas)
                    @if (!(get_option('saas_org_default_package_type') == 'monthly' && $saas->yearly_price <= 1)) <li
                        class="nav-item" role="presentation">
                        <div class="nav-link {{ ++$first == 1 ? 'active' : '' }}"
                            id="pills-organization-yearly-{{ $saas->id }}-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-organization-yearly-{{ $saas->id }}" type="button" role="tab"
                            aria-controls="pills-organization-yearly-{{ $saas->id }}"
                            aria-selected="{{ $first == 1 ? 'true' : 'false' }}">
                            <div class="landing-tab-price">
                                <div class="price-label-logo">
                                    <img src="{{ getImageFile($saas->icon) }}" alt="{{ $saas->title }}"
                                        class=" rounded-circle">
                                    <h6>{{ $saas->title }}</h6>
                                </div>

                                <div class="price-label">
                                    @if ($saas->discounted_yearly_price < 1) <h4 class="price-sub-title">{{ __('Full
                                        Free') }}</h6>
                                        @else
                                        <h4 class="price-sub-title">
                                            {{ $saas->discounted_yearly_price . ($currencySymbol ??
                                            get_currency_symbol()) }}/ {{ __('Month') }}
                                        </h4>
                                        @if ($saas->discounted_yearly_price != $saas->yearly_price)
                                        <p class="price-currency text-decoration-line-through">
                                            {{ $saas->yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                        </p>
                                        @endif
                                        @endif
                                </div>

                            </div>
                        </div>
                        </li>
                        @endif
                        @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

</div>
</div>