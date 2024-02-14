<div class="tab-pane fade show active overflow-x-scroll" id="pills-monthly" role="tabpanel"
    aria-labelledby="pills-monthly-tab">
    <table class="table text-center theme-border bg-light mt-56 position-relative mb-136 price-table">
        <thead>
            <tr class="price-title-bg">
                <th scope="col">
                    <h6 class="our-plan">{{ __('Subscription Plan') }}</h6>
                </th>

                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <th scope="col">
                            <div class="price-box-title">
                                <img src="{{ getImageFile($subscription->icon) }}" alt="{{ $subscription->title }}">
                                <h5 class="price-labe">{{ $subscription->title }}</h5>
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
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            <div class="price-sub-title-part">
                                @if ($subscription->discounted_monthly_price < 1)
                                    <h4 class="price-sub-title">{{ __('Full Free') }}</h6>
                                @else
                                    <h4 class="price-sub-title">
                                        {{ $subscription->discounted_monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                    </h4>
                                    @if ($subscription->discounted_monthly_price != $subscription->monthly_price)
                                        <p class="price-currency text-decoration-line-through">
                                            {{ $subscription->monthly_price . ($currencySymbol ?? get_currency_symbol()) }}
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
                    <p class="title-price-list">{{ __('Unlimited access to course') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            @if ($subscription->course > 0)
                                <p class="title-price-list">{{ $subscription->course }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Access to Bundle Courses') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            @if ($subscription->bundle_course > 0)
                                <p class="title-price-list">{{ $subscription->bundle_course }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Buy Consultancy Hour') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            @if ($subscription->consultancy > 0)
                                <p class="title-price-list">{{ $subscription->consultancy }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Device Access') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            @if ($subscription->device > 0)
                                <p class="title-price-list">{{ $subscription->device }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-btu-line">
                <td>

                </td>
                @php
                    $matched = $subscriptions->where('id', @$mySubscriptionPackage->package_id)->first();
                    $disabledYearly = !is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false;
                    $disabledMonthly = $disabledYearly ? true : (!is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? true : false);
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'yearly' && $subscription->monthly_price <= 1))
                        <td>
                            <form method="post"
                                action="{{ route('student.subscription.checkout', $subscription->uuid) }}">
                                @csrf
                                <input type="hidden" name="monthly" value=1>
                                <button type="submit" {{ $disabledMonthly ? 'disabled' : '' }}
                                    class="{{ $disabledMonthly ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1">{{ $disabledMonthly &&
                                    ($subscription->id == @$mySubscriptionPackage->package_id && @$mySubscriptionPackage->subscription_type) ==
                                        SUBSCRIPTION_TYPE_MONTHLY
                                        ? __("Current
                                                                                                                                        Plan")
                                        : __('Get Started') }}</button>
                            </form>
                        </td>
                        @php
                            if ($disabledMonthly && $subscription->id == $matched->id) {
                                $disabledMonthly = false;
                            }
                        @endphp
                    @endif
                @endforeach
            </tr>
        </tbody>
    </table>

</div>
<div class="tab-pane fade overflow-x-scroll" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
    <table class="table text-center theme-border bg-light mt-56 position-relative mb-136 price-table">
        <thead>
            <tr class="price-title-bg">
                <th scope="col">
                    <h6 class="our-plan">{{ __('Subscription Plan') }}</h6>
                </th>

                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <th scope="col">
                            <div class="price-box-title">
                                <img src="{{ getImageFile($subscription->icon) }}" alt="{{ $subscription->title }}">
                                <h5 class="price-labe">{{ $subscription->title }}</h5>
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
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            <div class="price-sub-title-part">
                                @if ($subscription->discounted_yearly_price < 1)
                                    <h4 class="price-sub-title">{{ __('Full Free') }}</h6>
                                    @else
                                        <h4 class="price-sub-title">
                                            {{ $subscription->discounted_yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
                                        </h4>
                                        @if ($subscription->discounted_yearly_price != $subscription->yearly_price)
                                            <p class="price-currency text-decoration-line-through">
                                                {{ $subscription->yearly_price . ($currencySymbol ?? get_currency_symbol()) }}
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
                    <p class="title-price-list">{{ __('Unlimited access to course') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            @if ($subscription->course > 0)
                                <p class="title-price-list">{{ $subscription->course }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Access to Bundle Courses') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            @if ($subscription->bundle_course > 0)
                                <p class="title-price-list">{{ $subscription->bundle_course }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Buy Consultancy Hour') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            @if ($subscription->consultancy > 0)
                                <p class="title-price-list">{{ $subscription->consultancy }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-body-list">
                <th scope="row">
                    <p class="title-price-list">{{ __('Device Access') }}</p>
                </th>
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            @if ($subscription->device > 0)
                                <p class="title-price-list">{{ $subscription->device }}</p>
                            @else
                                <p class="title-price-list"><i class="fas fa-times"></i></p>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <tr class="price-btu-line">
                <td>

                </td>
                @php
                    $matched = $subscriptions->where('id', @$mySubscriptionPackage->package_id)->first();
                    $disabledYearly = !is_null($matched) && $mySubscriptionPackage->subscription_type == SUBSCRIPTION_TYPE_YEARLY ? true : false;
                @endphp
                @foreach ($subscriptions as $index => $subscription)
                    @if (!(get_option('subscription_default_package_type') == 'monthly' && $subscription->yearly_price <= 1))
                        <td>
                            <form method="post"
                                action="{{ route('student.subscription.checkout', $subscription->uuid) }}">
                                @csrf
                                <input type="hidden" name="monthly" value=0>
                                <button type="submit" {{ $disabledYearly ? 'disabled' : '' }}
                                    class="{{ $disabledYearly ? 'disabled-btn' : '' }} package-btn green-theme-btn theme-button1">{{ $disabledYearly &&
                                    ($subscription->id == @$mySubscriptionPackage->package_id && @$mySubscriptionPackage->subscription_type) ==
                                        SUBSCRIPTION_TYPE_MONTHLY
                                        ? __("Current
                                                                                                                                        Plan")
                                        : __('Get Started') }}</button>
                            </form>
                        </td>
                        @php
                            if ($disabledYearly && $subscription->id == $matched->id) {
                                $disabledYearly = false;
                            }
                        @endphp
                    @endif
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
