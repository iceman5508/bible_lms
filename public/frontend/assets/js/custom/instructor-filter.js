(function ($) {
    "use strict";

    $("#slider-range-instructor").slider({
        range: true,
        orientation: "horizontal",
        min: 0,
        max: maxPrice,
        values: [0, maxPrice],
        step: 1,

        slide: function (event, ui) {
            if (ui.values[0] == ui.values[1]) {
                return false;
            }

            $("#price-min").val(ui.values[0]);
            $("#price-max").val(ui.values[1]);
            filterData();
        }
    });

    $("#price-min").val($("#slider-range-instructor").slider("values", 0));
    $("#price-max").val($("#slider-range-instructor").slider("values", 1));

    //map data prepare
    window.map = L.mapbox.map('map')
        .setView([-37.82, 175.215], 14)
        .addLayer(L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'));
    
    window.markers = L.markerClusterGroup({
        iconCreateFunction: function (cluster) {
            var childCount = cluster.getChildCount();
            return new L.DivIcon({ html: '<div><span>' + childCount + '</span></div>', className: 'd-flex justify-content-center align-items-center bg-theme font-bold rounded-5 text-white', iconSize: [40, 40] });
        }
    });

    mapData.forEach(feature => {
        var popupContent =  feature.properties.popup;
        var marker = L.marker(new L.LatLng(feature.coordinates.lat, feature.coordinates.long), {
                icon: L.icon({
                    iconUrl: feature.properties.image,
                    iconSize:[40, 40],
                    className:'border border-3 border-light rounded-5',
                }),
                title: feature.properties.name
            });
        marker.bindPopup(popupContent);
        window.markers.addLayer(marker);
    });
    
    window.map.addLayer(window.markers);
    window.map.fitBounds(window.markers.getBounds())

    window.page = 1;

    $(document).on('click', '#loadMoreBtn', function () {
        window.page++;
        let data = requestData();
        data['page'] = window.page;
        $.ajax({
            type: "GET",
            url: paginateRoute,
            data: data,
            datatype: "json",
            success: function (res) {
                if(res.status == true){
                    $('#instructorBlock').append(res.html);
                    res.mapData.forEach((feature) => {
                        var popupContent =  feature.properties.popup;
                        let marker = L.marker(new L.LatLng(feature.coordinates.lat, feature.coordinates.long), {
                            icon: L.icon({
                                iconUrl: feature.properties.image,
                                iconSize:[40, 40],
                                className:'border border-3 border-light rounded-5',
                            }),
                            title: feature.properties.name
                        });
                        marker.bindPopup(popupContent);
                        window.markers.addLayer(marker);
                        window.map.fitBounds(window.markers.getBounds())
                    });

                    if(res.lastPage == true){
                        $("#loadMoreBtn").removeClass('d-block')
                        $("#loadMoreBtn").addClass('d-none')
                    }
                }
            }
        });
    });

    
})(jQuery)

function filterData(){

    $.ajax({
        type: "GET",
        url: filterRoute,
        data: requestData(),
        datatype: "json",
        beforeSend: function () {
            $("#loading").removeClass('d-none');
        },
        complete: function () {
            $("#loading").addClass('d-none');
        },
        success: function (response) {
            markers.clearLayers();
            response.mapData.forEach((feature) => {
                var popupContent =  feature.properties.popup;
                let marker = L.marker(new L.LatLng(feature.coordinates.lat, feature.coordinates.long), {
                    icon: L.icon({
                        iconUrl: feature.properties.image,
                        iconSize:[40, 40],
                        className:'border border-3 border-light rounded-5',
                    }),
                    title: feature.properties.name
                });

                marker.bindPopup(popupContent);
                window.markers.addLayer(marker);
                window.map.fitBounds(window.markers.getBounds())
            });
            $('#instructorParentBlock').html(response.html);
            window.page = 1;
        },
        error: function () {
            alert("Error!");
        },
    });
}

function requestData(){
    return {
        "available_for_meeting": ($(':input[name=available_for_meeting]').is(':checked') ? 1 : 0),
        "free_meeting": ($(':input[name=free_meeting]').is(':checked') ? 1 : 0),
        "discount_meeting": ($(':input[name=discount_meeting]').is(':checked') ? 1 : 0),
        "country_id": $(':input[name=country]').val(),
        "state_id": $(':input[name=state]').val(),
        "city_id": $(':input[name=city]').val(),
        "price_min" : $(':input[name=price_min]').val(),
        "price_max" : $(':input[name=price_max]').val(),
        "sort_by" : $(':input[name=sort_by]').val(),
        "available_type" : $(':input[name=available_type]:checked').val(),
        "consultation_day[]" : $("input[name^='consultation_day']").map(function() {
            if($(this).is(':checked')){
                return this.value;
            }
        }).get(),
        "category_ids[]" : $("input[name^='category_ids']").map(function() {
            if($(this).is(':checked')){
                return this.value;
            }
        }).get(),
        "rating[]" : $("input[name^='rating']").map(function() {
            if($(this).is(':checked')){
                return this.value;
            }
        }).get(),
    }
}
