(function ($) {
    "use strict";
    $(document).ready(function () {
        $(document).on('change', ".filterSortBy", function () {
            var sortBy_id = this.value;
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            postFilter(response.data, response.route)
        });

        $(document).on('click', ".filterPrice", function () {
            var sortBy_id = $('.filterSortBy option:selected').val();
            var min_price = $('.min_price').val()
            var max_price = $('.max_price').val()
            var response = allFilterData(sortBy_id, min_price, max_price)
            postFilter(response.data, response.route)
        });

        function postFilter(data, route) {
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                datatype: "json",
                beforeSend: function () {
                    $('.appendBundleCourseList').addClass('d-none');
                    $("#loading").removeClass('d-none');
                },
                complete: function () {
                    $("#loading").addClass('d-none');
                    $('.appendBundleCourseList').removeClass('d-none');
                },
                success: function (response) {
                    $('.appendBundleCourseList').html(response)
                },
                error: function () {
                    alert("Error!");
                },
            });
        }

        function allFilterData(sortBy_id, min_price, max_price) {
            var data = {
                "min_price": min_price, "max_price": max_price, "sortBy_id": sortBy_id,
            }
            var route = $('.route').val();

            return {data, route};
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
                    $('.appendBundleCourseList').html(response);
                }
            });
        }
    });
})(jQuery)
