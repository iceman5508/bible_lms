<!-- Saas Subscription Plan Section start -->
<section class="subscription-plan-area saas-subscription-plan-area bg-light section-t-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h3 class="section-heading">{{ __('SaaS Plan') }}</h3>
                    <p class="section-sub-heading">{{ __('#Choose a saas plan and save money!') }}</p>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills saas-plan-instructor-organization-nav radius-8 mb-4" id="pills-tab1" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-Instructor-tab" data-bs-toggle="pill" data-bs-target="#pills-Instructor" type="button" role="tab" aria-controls="pills-Instructor" aria-selected="true">
                    {{ __("Instructor") }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-Organization-tab" data-bs-toggle="pill" data-bs-target="#pills-Organization" type="button" role="tab" aria-controls="pills-Organization" aria-selected="false">
                    {{ __("Organization") }}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tab1Content">
            <div class="tab-pane fade show active" id="pills-Instructor" role="tabpanel" aria-labelledby="pills-Instructor-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                        <div class="pricing-tab-nav tp-tab mb-50 mx-auto">
                            <nav class="pricing-tab-inner-nav d-inline-flex align-items-center">
                                <div class="plan-switch-month-year-text mx-3">{{ __("Monthly") }}</div>
                                <div class="nav nav-tabs price-tab-slide justify-content-center" id="nav-tab1" role=tablist>
                                    <label for="price-tab-check1" class="nav justify-content-center">
                                        <span class="nav-link active" id="nav-monthly-tab1" data-bs-toggle="tab" data-bs-target="#nav-monthly1" role="tab" aria-controls="nav-monthly1" aria-selected="false"></span>
                                        <input type="checkbox" id="price-tab-check1">
                                        <span class="nav-link" id="nav-yearly-tab1" data-bs-toggle="tab" data-bs-target="#nav-yearly1" role="tab" aria-controls="nav-yearly1" aria-selected="true"></span>
                                    </label>
                                </div>
                                <div class="plan-switch-month-year-text mx-3">{{ __("Yearly") }}</div>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        @php 
                        $matched = $instructorSaas->where('id', @$mySaasPackage->package_id)->first();
                        $disabledYearly = (!is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false);
                        $disabledMonthly = ($disabledYearly) ? true : (!is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
                        @endphp
                        <div class="tab-content" id="nav-tab1Content">
                            <div class="tab-pane fade show active" id="nav-monthly1" role="tabpanel" aria-labelledby="nav-monthly-tab1">
                                <div class="subscription-slider-items owl-carousel owl-theme">
                                    @foreach($instructorSaas as $index => $saas)
                                    @if(!(get_option('saas_ins_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                    @include('frontend.partials.package_card', ['isMonthly' => 1, 'package' => $saas, 'isCurrent' => ($saas->id == @$mySaasPackage->package_id && @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY),'isDisabled' => $disabledMonthly])
                                    @php 
                                    if($disabledMonthly && $saas->id == $matched->id){
                                        $disabledMonthly = false;
                                    }
                                    @endphp
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-yearly1" role="tabpanel" aria-labelledby="nav-yearly-tab1">
                                <div class="subscription-slider-items owl-carousel owl-theme">
                                    @foreach($instructorSaas as $index => $saas)
                                    @if(!(get_option('saas_ins_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                    @include('frontend.partials.package_card', ['isMonthly' => 0, 'package' => $saas, 'isCurrent' => ($saas->id == @$mySaasPackage->package_id && @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY),'isDisabled' => $disabledYearly])
                                    @php 
                                    if($disabledYearly && $saas->id == $matched->id){
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

            <div class="tab-pane fade" id="pills-Organization" role="tabpanel" aria-labelledby="pills-Organization-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                        <div class="pricing-tab-nav tp-tab mb-50 mx-auto">
                            <nav class="pricing-tab-inner-nav d-inline-flex align-items-center">
                                <div class="plan-switch-month-year-text mx-3">{{ __("Monthly") }}</div>
                                <div class="nav nav-tabs price-tab-slide justify-content-center" id="nav-tab2" role=tablist>
                                    <label for="price-tab-check2" class="nav justify-content-center">
                                        <span class="nav-link active" id="nav-monthly-tab2" data-bs-toggle="tab" data-bs-target="#nav-monthly2" role="tab" aria-controls="nav-monthly2" aria-selected="false"></span>
                                        <input type="checkbox" id="price-tab-check2">
                                        <span class="nav-link" id="nav-yearly-tab2" data-bs-toggle="tab" data-bs-target="#nav-yearly2" role="tab" aria-controls="nav-yearly2" aria-selected="true"></span>
                                    </label>
                                </div>
                                <div class="plan-switch-month-year-text mx-3">{{ __("Yearly") }}</div>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="tab-content" id="nav-tab2Content">
                            @php 
                            $matched = $organizationSaas->where('id', @$mySaasPackage->package_id)->first();
                            $disabledYearly = (!is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false);
                            $disabledMonthly = ($disabledYearly) ? true : (!is_null($matched) && $mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
                            @endphp
                            <div class="tab-pane fade show active" id="nav-monthly2" role="tabpanel" aria-labelledby="nav-monthly-tab2">
                                <div class="subscription-slider-items owl-carousel owl-theme">
                                    @foreach($organizationSaas as $index => $saas)
                                    @if(!(get_option('saas_org_default_package_type') == 'yearly' && $saas->monthly_price <= 1))
                                    @include('frontend.partials.package_card', ['isMonthly' => 1, 'package' => $saas, 'isCurrent' => ($saas->id == @$mySaasPackage->package_id && @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY),'isDisabled' => $disabledMonthly])
                                    @php 
                                    if($disabledMonthly && $saas->id == $matched->id){
                                        $disabledMonthly = false;
                                    }
                                    @endphp
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-yearly2" role="tabpanel" aria-labelledby="nav-yearly-tab2">
                                <div class="subscription-slider-items owl-carousel owl-theme">
                                    @foreach($organizationSaas as $index => $saas)
                                    @if(!(get_option('saas_org_default_package_type') == 'monthly' && $saas->yearly_price <= 1))
                                    @include('frontend.partials.package_card', ['isMonthly' => 0, 'package' => $saas, 'isCurrent' => ($saas->id == @$mySaasPackage->package_id && @$mySaasPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY),'isDisabled' => $disabledYearly])
                                    @php 
                                    if($disabledYearly && $saas->id == $matched->id){
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

        </div>
    </div>
</section>
<!-- Saas Subscription Plan Section end -->
