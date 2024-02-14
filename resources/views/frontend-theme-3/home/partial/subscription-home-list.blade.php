@php
$matched = $subscriptions->where('id', @$mySubscriptionPackage->package_id)->first();
$disabledYearly = (!is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true :
false);
$disabledMonthly = ($disabledYearly) ? true : (!is_null($matched) && $mySubscriptionPackage->subscription_type ==
SUBSCRIPTION_TYPE_MONTHLY ? true : false);
@endphp
<div class="tab-pane fade show active overflow-x-scroll" id="pills-monthly" role="tabpanel"
    aria-labelledby="pills-monthly-tab">
    <div class="row">
        <div class="col-lg-7 mb-4  order-lg-first">
            <div class="tab-content">
                @php
                $first = 0;
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <=
                    1)) <div class="tab-pane fade {{ ++$first == 1 ? 'show active' : '' }}"
                    id="pills-monthly-{{ $subscription->id }}" role="tabpanel"
                    aria-labelledby="pills-monthly-{{ $subscription->id }}-tab">
                    <div class="price-list-content">
                        <ul>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __('Unlimited access to').' '.
                                    $subscription->course. __(' course') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __('Access to').' '.
                                    $subscription->bundle_course.' '.__('bundle course') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __("Buy") .' '. $subscription->consultancy.'
                                    '.__('Consultancy Hour') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ $subscription->device .' '. __("Devices Access")
                                    }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                        </ul>
                        <form method="post" action="{{ route('student.subscription.checkout', $subscription->uuid) }}">
                            @csrf
                            <input type="hidden" name="monthly" value=1>
                            <button type="submit" {{ ($disabledMonthly) ? 'disabled' : '' }}
                                class="{{ ($disabledMonthly) ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1 mt-25">{{
                                ($disabledMonthly && ($subscription->id == @$mySubscriptionPackage->package_id &&
                                @$mySubscriptionPackage->subscription_type) == SUBSCRIPTION_TYPE_MONTHLY) ? __("Current
                                Plan") : __("Get Started") }}</button>
                        </form>
                    </div>
            </div>
            @php
            if($disabledMonthly && $subscription->id == $matched->id){
            $disabledMonthly = false;
            }
            @endphp
            @endif
            @endforeach
        </div>
    </div>
    <div class="col-lg-5 mb-4 order-first order-lg-last">
        <div class="price-landing-page-tab">
            <ul class="sf-sass-plan nav-pills" id="pills-tab" role="tablist">
                @php
                $first = 0;
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <=
                    1)) <li class="nav-item" role="presentation">
                    <div class="nav-link {{ ++$first == 1 ? 'active' : '' }}"
                        id="pills-monthly-{{ $subscription->id }}-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-monthly-{{ $subscription->id }}" type="button" role="tab"
                        aria-controls="pills-monthly-{{ $subscription->id }}"
                        aria-selected="{{ $first == 1 ? 'true' : 'false' }}">
                        <div class="landing-tab-price">
                            <div class="price-label-logo">
                                <img src="{{ getImageFile($subscription->icon) }}" alt="{{ $subscription->title }}"
                                    class=" rounded-circle">
                                <h6>{{ $subscription->title }}</h6>
                            </div>

                            <div class="price-label">
                                @if ($subscription->discounted_monthly_price < 1) <h4 class="price-sub-title">{{
                                    __('Full Free') }}</h6>
                                    @else
                                    <h4 class="price-sub-title">
                                        {{ $subscription->discounted_monthly_price . ($currencySymbol ??
                                        get_currency_symbol()) }}/ {{ __('Month') }}
                                    </h4>
                                    @if ($subscription->discounted_monthly_price != $subscription->monthly_price)
                                    <p class="price-currency text-decoration-line-through">
                                        {{ $subscription->monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
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
<div class="tab-pane fade overflow-x-scroll" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
    <div class="row">
        <div class="col-lg-7 mb-4 order-lg-first">

            <div class="tab-content">
                @php
                $first = 0;
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                @if(!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                    <div class="tab-pane fade {{ ++$first == 1 ? 'show active' : '' }}"
                    id="pills-yearly-{{ $subscription->id }}" role="tabpanel"
                    aria-labelledby="pills-yearly-{{ $subscription->id }}-tab">
                    <div class="price-list-content">
                        <ul>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __('Unlimited access to').' '.
                                    $subscription->course. __(' course') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __('Access to').' '.
                                    $subscription->bundle_course.' '.__('bundle course') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ __("Buy") .' '. $subscription->consultancy.'
                                    '.__('Consultancy Hour') }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                            <li class="align-items-center d-flex justify-content-between">
                                <p class="section-sub-heading m-0">{{ $subscription->device .' '. __("Devices Access")
                                    }}</p>
                                <span class="iconify" data-icon="bi:check-lg"></span>
                            </li>
                        </ul>
                        <form method="post" action="{{ route('student.subscription.checkout', $subscription->uuid) }}">
                            @csrf
                            <input type="hidden" name="monthly" value=0>
                            <button type="submit" {{ ($disabledYearly) ? 'disabled' : '' }}
                                class="{{ ($disabledYearly) ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1 mt-25">{{
                                ($disabledYearly && ($subscription->id == @$mySubscriptionPackage->package_id &&
                                @$mySubscriptionPackage->subscription_type) == SUBSCRIPTION_TYPE_MONTHLY) ? __("Current
                                Plan") : __("Get Started") }}</button>
                        </form>
                    </div>
            </div>
            @php
            if($disabledYearly && $subscription->id == $matched->id){
            $disabledYearly = false;
            }
            @endphp
            @endif
            @endforeach
        </div>
    </div>
    <div class="col-lg-5 mb-4 order-first order-lg-last">
        <div class="price-landing-page-tab">
            <ul class="sf-sass-plan nav-pills" role="tablist">
                @php
                $first = 0;
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                @if(!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                    <li class="nav-item" role="presentation">
                    <div class="nav-link {{ ++$first == 1 ? 'active' : '' }}"
                        id="pills-yearly-{{ $subscription->id }}-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-yearly-{{ $subscription->id }}" type="button" role="tab"
                        aria-controls="pills-yearly-{{ $subscription->id }}"
                        aria-selected="{{ $first == 1 ? 'true' : 'false' }}">
                        <div class="landing-tab-price">
                            <div class="price-label-logo">
                                <img src="{{ getImageFile($subscription->icon) }}" alt="{{ $subscription->title }}"
                                    class=" rounded-circle">
                                <h6>{{ $subscription->title }}</h6>
                            </div>

                            <div class="price-label">
                                @if ($subscription->discounted_yearly_price < 1) <h4 class="price-sub-title">{{ __('Full
                                    Free') }}</h6>
                                    @else
                                    <h4 class="price-sub-title">
                                        {{ $subscription->discounted_yearly_price . ($currencySymbol ??
                                        get_currency_symbol()) }}/ {{ __('Yearly') }}
                                    </h4>
                                    @if ($subscription->discounted_yearly_price != $subscription->yearly_price)
                                    <p class="price-currency text-decoration-line-through">
                                        {{ $subscription->yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
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