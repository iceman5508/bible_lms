<!-- Subscription Plan Section start -->
<section class="subscription-plan-area bg-light section-t-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h3 class="section-heading">{{ __('Subscribe Now!') }}</h3>
                    <p class="section-sub-heading">{{ __('#Choose a subscription plan and save money!') }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                <div class="pricing-tab-nav tp-tab mb-50 mx-auto">
                    <nav class="pricing-tab-inner-nav d-inline-flex align-items-center">
                        <div class="plan-switch-month-year-text mx-3">{{ __("Monthly") }}</div>
                        <div class="nav nav-tabs price-tab-slide justify-content-center" id="nav-tab" role=tablist>
                            <label for="price-tab-check" class="nav justify-content-center">
                                <span class="nav-link active" id="nav-monthly-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-monthly" role="tab" aria-controls="nav-monthly"
                                    aria-selected="false"></span>
                                <input type="checkbox" id="price-tab-check">
                                <span class="nav-link" id="nav-yearly-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-yearly" role="tab" aria-controls="nav-yearly"
                                    aria-selected="true"></span>
                            </label>
                        </div>
                        <div class="plan-switch-month-year-text mx-3">{{ __("Yearly") }}</div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                <div class="tab-content" id="nav-tabContent">
                    @php 
                        $matched = $subscriptions->where('id', @$mySubscriptionPackage->package_id)->first();
                        $disabledYearly = (!is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false);
                        $disabledMonthly = ($disabledYearly) ? true : (!is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
                        @endphp
                    <div class="tab-pane fade show active" id="nav-monthly" role="tabpanel"
                        aria-labelledby="nav-monthly-tab">
                        <div class="subscription-slider-items owl-carousel owl-theme">
                            @foreach($subscriptions as $index => $subscription)
                            @if(!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                            @include('frontend.partials.package_card', ['isMonthly' => 1, 'package' => $subscription, 'isCurrent' => ($subscription->id == @$mySubscriptionPackage->package_id && @$mySubscriptionPackage->subscription_type) == SUBSCRIPTION_TYPE_MONTHLY, 'isDisabled' => $disabledMonthly])
                            @php 
                            if($disabledMonthly && $subscription->id == $matched->id){
                                $disabledMonthly = false;
                            }
                            @endphp
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-yearly" role="tabpanel" aria-labelledby="nav-yearly-tab">
                        <div class="subscription-slider-items owl-carousel owl-theme">
                            @foreach($subscriptions as $index => $subscription)
                            @if(!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                            @include('frontend.partials.package_card', ['isMonthly' => 0, 'package' => $subscription, 'isCurrent' => ($subscription->id == @$mySubscriptionPackage->package_id && @$mySubscriptionPackage->subscription_type) == SUBSCRIPTION_TYPE_MONTHLY, 'isDisabled' => $disabledYearly])
                            @php 
                            if($disabledYearly && $subscription->id == $matched->id){
                                $disabledYearly = false;
                            }
                            @endphp
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Subscription Plan Section end -->