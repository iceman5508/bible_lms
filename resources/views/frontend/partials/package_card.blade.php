@php
$price = ($isMonthly) ? $package->discounted_monthly_price : $package->discounted_yearly_price;
$old_price = ($isMonthly) ? $package->monthly_price : $package->yearly_price;
@endphp
<div class="pricing-item position-relative  theme-border radius-8 mb-40 {{ $package->recommended ? 'most-popular-plan' : '' }}">
    @if($package->recommended)
    <div class="most-popular-content position-absolute text-white font-14 text-center w-100">{{ __('Recomended') }}</div>
    @endif
    <div class="pricing-content-box">
        <div class="pricing-top text-center">
            <div class="pricing-icon mb-30">
                <img src="{{ getImageFile($package->icon) }}" alt="{{ $package->title }}">
            </div>
            <div class="pricing-title-wrapper mb-30">
                <h4 class="pricing-title">{{ __($package->title) }}</h4>
                <p class="font-semi-bold text-decoration-line-through"></p>
            </div>
        </div>
        <div class="pricing-time-duration d-flex theme-border text-center border-start-0 border-end-0 mb-30">
            @if($price < 1)
            <h6 class="font-22 text-center font-bold">{{ __('Full Free') }}</h6>
            @else
            <h6 class="font-22 font-bold">{{ $price.($currencySymbol ?? get_currency_symbol()) }}/{{ ($isMonthly) ? __('Month') : __('Yearly') }}</h6>
            @if($price != $old_price)
            <p class="font-semi-bold text-decoration-line-through">{{ $old_price.($currencySymbol ?? get_currency_symbol())}}</p>
            @endif
            @endif

        </div>
        <div class="pricing-content">
            <div class="pricing-content-inner d-flex flex-column justify-content-between">
                <div class="pricing-feature mb-30">
                    <ul>
                        @if($package->package_type == PACKAGE_TYPE_SUBSCRIPTION)
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __('Unlimited access to').' '. $package->course. __(' course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __('Access to').' '. $package->bundle_course.' '.__('bundle course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Buy") .' '. $package->consultancy.' '.__('Consultancy Hour') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ $package->device .' '. __("Devices Access") }}
                        </li>
                        @elseif($package->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR)
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __('Unlimited Create ').' '. $package->course. ' '.__('Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Create") .' '. $package->bundle_course.' '.__('Bundle Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Enable") .' '. $package->subscription_course.' '.__('Subscription Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Give") .' '. $package->consultancy.' '.__('hour consultancy') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Minimum of") .' '. $package->admin_commission .'% '.__('sale commission')}}
                        </li>
                        @elseif($package->package_type == PACKAGE_TYPE_SAAS_ORGANIZATION)
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Unlimited create of").' '. $package->instructor.' '.__('instructor') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Create ").' '. $package->student.' '.__('student') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __('Unlimited Create ').' '. $package->course. ' '.__('Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Create") .' '. $package->bundle_course.' '.__('Bundle Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Enable") .' '. $package->subscription_course.' '.__('Subscription Course') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Give") .' '. $package->consultancy.' '.__('hour consultancy') }}
                        </li>
                        <li>
                            <span class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                            <span class="iconify" data-icon="bi:check-lg"></span></span>{{ __("Minimum of") .' '. $package->admin_commission .'% '.__('sale commission')}}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form method="post" action="{{ route('student.subscription.checkout', $package->uuid) }}">
        @csrf
        <input type="hidden" name="monthly" value={{ $isMonthly }}>
        <div class="pricing-btn text-center">
            <button type="submit" {{ ($isDisabled) ? 'disabled' : '' }} class="{{ ($isDisabled) ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1">{{ ($isDisabled && $isCurrent) ? __("Current Plan") : __("Get Started") }}</button>
        </div>
    </form>
</div>
