(function ($) {
    "use strict";
    $(document).ready(function () {
        $(document).on('change', ".filterSortBy", function () {
            var sortBy_id = this.value;
            var min_hourly_rate = $('.min_hourly_rate').val()
            var max_hourly_rate = $('.max_hourly_rate').val()
            var search_name = $('.search_name').val()
            var response = allFilterData(sortBy_id, min_hourly_rate, max_hourly_rate, search_name)
            postFilter(response.data, response.route)
        });

        $(document).on('click', ".filterRating, .filterHourlyRate, .filterSearchName, .filterType", function () {
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_hourly_rate = $('.min_hourly_rate').val()
            var max_hourly_rate = $('.max_hourly_rate').val()
            var search_name = $('.search_name').val()
            var response = allFilterData(sortBy_id, min_hourly_rate, max_hourly_rate, search_name)
            postFilter(response.data, response.route)
        });

        function postFilter(data, route) {
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                datatype: "json",
                beforeSend: function () {
                    $('.appendConsultationList').addClass('d-none');
                    $("#loading").removeClass('d-none');
                },
                complete: function () {
                    $("#loading").addClass('d-none');
                    $('.appendConsultationList').removeClass('d-none');
                },
                success: function (response) {
                    $('.appendConsultationList').html(response)
                },
                error: function () {
                    alert("Error!");
                },
            });
        }

        function allFilterData(sortBy_id, min_hourly_rate, max_hourly_rate, search_name) {
            var ratingIds = [];
            $("input[name='filterRating']:checked").each(function () {
                ratingIds.push($(this).val());
            });

            var typeIds = [];
            $("input[name='filterType']:checked").each(function () {
                typeIds.push($(this).val());
            });

            var data = {
                "min_hourly_rate": min_hourly_rate,
                "max_hourly_rate": max_hourly_rate,
                "sortBy_id": sortBy_id,
                "ratingIds": ratingIds,
                "search_name": search_name,
                'typeIds': typeIds
            }
            var route = $('.route').val();

            return {data, route};
        }

        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var route = $('.fetch-data-route').val() + '?page=' + page;
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_hourly_rate = $('.min_hourly_rate').val()
            var max_hourly_rate = $('.max_hourly_rate').val()
            var search_name = $('.search_name').val()
            var response = allFilterData(sortBy_id, min_hourly_rate, max_hourly_rate, search_name)
            fetch_data(response.data, route)
        });

        function fetch_data(data, route) {
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                success: function (response) {
                    $('.appendConsultationList').html(response);
                }
            });
        }
    });
})(jQuery)
