<!-- Courses Sidebar start-->
<div class="col-md-4 col-lg-3 col-xl-3 coursesLeftSidebar">
    <div class="courses-sidebar-area consultation-sidebar-area bg-light">

        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                        {{ __('Search Instructor Name') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        <div class="consult-instructor-search-box">
                            <div class="range-value-wrap instructor-consult-search-by-name-wrap">
                                <input type="search" class="price-range-field search_name" placeholder="{{ __('Search') }}" />
                            </div>
                            <div class="range-value-wrap-go-btn d-flex align-items-center">
                                <button type="button" class="filterSearchName text-white font-17 d-flex align-items-center"><span class="iconify" data-icon="material-symbols:arrow-forward-rounded"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        {{ __('Type') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterType" type="checkbox" name="filterType" id="exampleRadiosType32" value="1">
                                <label class="form-check-label" for="exampleRadiosType32">
                                    {{ __('In-person') }}
                                </label>
                            </div>
                        </div>
                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterType" type="checkbox" name="filterType" id="exampleRadiosType33" value="2">
                                <label class="form-check-label" for="exampleRadiosType33">
                                    {{ __('Online') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                        {{ __('Hourly Rate') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingFour">
                    <div class="accordion-body">
                        <div class="range-value-box">
                            <div class="range-value-wrap"><label for="min_hourly_rate">{{ get_option('app_currency') }}{{ __('Min') }}:</label>
                                <input  type="number" min=0 max="9900" id="" value="0" class="price-range-field min_hourly_rate" />
                            </div>
                            <div class="range-value-wrap"><label for="max_hourly_rate">{{ get_option('app_currency') }}{{ __('Max') }}:</label>
                                <input  type="number" min=0 max="10000" id="" value="{{ $highest_price }}" class="price-range-field max_hourly_rate" />
                            </div>
                            <div class="range-value-wrap-go-btn d-flex align-items-center">
                                <button type="button" class="filterHourlyRate text-white font-17 d-flex align-items-center"><span class="iconify" data-icon="material-symbols:arrow-forward-rounded"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                        {{ __('Rating') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios41" value="5">
                                <label class="form-check-label" for="exampleRadios41">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('5 star') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios42" value="4">
                                <label class="form-check-label" for="exampleRadios42">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('4 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios43" value="3">
                                <label class="form-check-label" for="exampleRadios43">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('3 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios44" value="2">
                                <label class="form-check-label" for="exampleRadios44">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('2 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios45" value="1">
                                <label class="form-check-label" for="exampleRadios45">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('1 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Courses Sidebar End-->
