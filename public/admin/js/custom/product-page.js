$('#price-range').rangeSlider(
    {
        settings: false,
        skin: 'red',
        type: 'interval',
        scale: true,
        trip: true,
        bar: true
    },
    { 
        step: 1, 
        values: [300,800],
        min: 0,
        max: 1000,
        step: 5
    });
    
    $('.list-view-btn').on('click', function(){
        $('.list-view-btn').addClass('active');
        $('.grid-view-btn').removeClass('active');
        document.getElementById('grid-style').style.display = "none";
        document.getElementById('list-style').style.display = "block";
    });
    
    $('.grid-view-btn').on('click', function(){
        $('.list-view-btn').removeClass('active');
        $('.grid-view-btn').addClass('active');
        document.getElementById('grid-style').style.display = "block";
        document.getElementById('list-style').style.display = "none";
    });