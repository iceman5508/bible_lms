(function ($) {
    "use strict";
    $(document).ready(function () {

        window.page = 1;

        $(document).on('click', '#loadMoreBtn', function () {
            window.page++;
            
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            let data = response.data;
            data['page'] = window.page;
            $.ajax({
                type: "GET",
                url: paginateRoute,
                data: response.data,
                datatype: "json",
                success: function (res) {
                    if(res.status == true){
                        $(document).find('#appendCourseList').append(res.html);
                        if(res.lastPage == true){
                            $(document).find("#loadMoreBtn").removeClass('d-block')
                            $(document).find("#loadMoreBtn").addClass('d-none')
                        }
                    }
                }
            });
        });

        $(document).on('change', ".filterSortBy", function () {
            var sortBy_id = this.value;
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            postFilter(response.data, response.route)
        });

        $(document).on('click', ".filterSubCategory, .filterDifficultyLevel, .filterRating, .filterLearnerAccessibility, .filterDuration, .filterPrice", function () {
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            postFilter(response.data, response.route)
        });

        function allFilterData(sortBy_id, min_price, max_price) {
            var category_id = $('.category_id').val();
            var sub_category_id = $('.sub_category_id').val();
            var route = $('.route').val();

            var subCategoryIds = [];
            $("input[name='filterSubCategory']:checked").each(function () {
                subCategoryIds.push($(this).val());
            });

            var difficultyLevelIds = [];
            $("input[name='filterDifficultyLevel']:checked").each(function () {
                difficultyLevelIds.push($(this).val());
            });

            var ratingIds = [];
            $("input[name='filterRating']:checked").each(function () {
                ratingIds.push($(this).val());
            });

            var learnerAccessibilityTypes = [];
            $("input[name='filterLearnerAccessibility']:checked").each(function () {
                learnerAccessibilityTypes.push($(this).val());
            });

            var durationIds = [];
            $("input[name='filterDuration']:checked").each(function () {
                durationIds.push($(this).val());
            });

            var data = {
                "category_id": category_id, "sub_category_id": sub_category_id, "subCategoryIds": subCategoryIds,
                "difficultyLevelIds": difficultyLevelIds, "ratingIds": ratingIds, "min_price": min_price, "max_price": max_price,
                "learnerAccessibilityTypes": learnerAccessibilityTypes, "durationIds": durationIds, "sortBy_id": sortBy_id,
            }
            return {data, route};
        }

        function postFilter(data, route) {
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                datatype: "json",
                beforeSend: function(){
                    $('#appendCourse').addClass('d-none');
                    $("#loading").removeClass('d-none');
                },
                complete: function(){
                    $("#loading").addClass('d-none');
                    $('#appendCourse').removeClass('d-none');
                },
                success: function (response) {
                    window.page = 1;
                    $('#appendCourse').html(response)
                },
                error: function () {
                    alert("Error!");
                },
            });
        }


        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var route = $('.fetch-data-route').val() + '?page=' + page;
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            fetch_data(response.data, route)
        });

        function fetch_data(data, route) {
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                success: function (response) {
                    $('#appendCourse').html(response);
                }
            });
        }
    });
})(jQuery)
