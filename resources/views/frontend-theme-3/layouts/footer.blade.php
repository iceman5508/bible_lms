<footer class="footer-area footer-gradient-bg position-relative landing-footer sf-landing-footer">
    <div class="section-overlay">
        <div class="container">
            <!-- footer-widget-area -->
            <div class="row footer-top-part section-p-t-b-90">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget footer-about">
                        <img src="{{ asset(get_option('app_logo')) }}" alt="Logo">
                        <p>{{ __(get_option('footer_quote')) }}</p>
                        <div class="footer-social mt-30">
                            <ul class="d-flex align-items-center">
                                @if (get_option('facebook_url'))
                                    <li><a href="{{ get_option('facebook_url') }}"><span class="iconify"
                                                data-icon="ant-design:facebook-filled"></span></a></li>
                                @endif
                                @if (get_option('twitter_url'))
                                    <li><a href="{{ get_option('twitter_url') }}"><span class="iconify"
                                                data-icon="ant-design:twitter-square-filled"></span></a></li>
                                @endif
                                @if (get_option('linkedin_url'))
                                    <li><a href="{{ get_option('linkedin_url') }}"><span class="iconify"
                                                data-icon="ant-design:linkedin-filled"></span></a></li>
                                @endif
                                @if (get_option('pinterest_url'))
                                    <li><a href="{{ get_option('pinterest_url') }}"><span class="iconify"
                                                data-icon="fa-brands:pinterest-square" data-width="1em"
                                                data-height="1em"></span></a></li>
                                @endif
                                @if (get_option('instagram_url'))
                                    <li><a href="{{ get_option('instagram_url') }}"><span class="iconify"
                                                data-icon="fa-brands:instagram-square" data-width="1em"
                                                data-height="1em"></span></a></li>
                                @endif
                                @if (get_option('tiktok_url'))
                                    <li><a href="{{ get_option('tiktok_url') }}"><span class="iconify"
                                                data-icon="iconoir:tiktok" data-width="1em"
                                                data-height="1em"></span></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-2 offset-lg-2">
                    <div class="footer-widget">
                        <h6 class="footer-widget-title">{{ __('Company') }}</h6>
                        <div class="footer-links d-flex">
                            <ul>
                                @foreach ($footerLeftMenus ?? [] as $footerLeftMenu)
                                    <li><a href="{{ $footerLeftMenu->url }}">{{ __($footerLeftMenu->name) }}</a></li>
                                @endforeach
                                {{-- <li><a href="{{ route('about') }}">{{ __('About')  }}</a></li>
                                <li><a href="{{ route('faq') }}">{{__('FAQ')}}</a></li>
                                <li><a href="{{ route('blogs') }}">{{ __('Blogs') }}</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                    <div class="footer-widget">
                        <h6 class="footer-widget-title">{{ __('Support') }}</h6>
                        <div class="footer-links d-flex">
                            <ul>
                                @foreach ($footerRightMenus ?? [] as $footerRightMenu)
                                    <li><a href="{{ $footerRightMenu->url }}">{{ __($footerRightMenu->name) }}</a>
                                    </li>
                                @endforeach

                                {{-- <li><a href="{{ route('contact') }}">{{  __('Contact')  }}</a></li>
                                <li><a href="{{ route('support-ticket-faq') }}">{{  __('Support')  }}</a></li>
                                @if (!get_option('private_mode') || !auth()->guest())
                                <li><a href="{{ route('courses') }}">{{ __('Courses')  }}</a></li>
                                @endif --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget footer-contact-info">
                        <h6 class="footer-widget-title">{{ __('Contact Info') }}</h6>

                        <div class="footer-links d-flex">
                            <ul>
                                <li><span class="iconify"
                                        data-icon="carbon:location-filled"></span><span>{{ __(get_option('app_location')) }}</span>
                                </li>
                                <li><span class="iconify"
                                        data-icon="fluent-emoji-high-contrast:telephone-receiver"></span><a
                                        href="tel:{{ get_option('app_contact_number') }}">{{ __(get_option('app_contact_number')) }}</a>
                                </li>
                                <li><span class="iconify" data-icon="ic:round-email"></span><a
                                        href="mailto:{{ get_option('app_email') }}">{{ __(get_option('app_email')) }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--copyright-text-->
            <div class="row copyright-wrapper">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="footer-payment">
                        <img src="{{ asset(get_option('app_footer_payment_image') ?? 'frontend/assets/img/payment-cards.png') }}"
                            alt="payments">
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="copyright-text text-center">
                        <p class="text-white font-13">{{ __(get_option('app_copyright')) }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 pe-lg-0">
                    <div class="footer-bottom-nav">
                        <ul class="d-flex justify-content-end">
                            <li><a href="{{ route('instructor') }}">{{ __('Instructor') }}</a></li>
                            <li><a
                                    href="{{ route('student.become-an-instructor') }}">{{ __('Become Instructor') }}</a>
                            </li>
                            <li><a href="{{ route('verify_certificate') }}">{{ __('Verify Certificate') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
