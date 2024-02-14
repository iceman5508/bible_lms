/*-------------------------------------------------------
All javascript and jquery plugins activation
-------------------------------------------------------*/
(function($){
    "use strict";

    // Preloader Start
    $(window).on('load', function () {
        $('#preloader_status').fadeOut();
        $('#preloader')
            .delay(350)
            .fadeOut('slow');
        $('body')
            .delay(350);

        // Onload Scroll To Top
        $(window).scrollTop(0);
    });
    // Preloader End

    /*---------------------------
    sidebar toggle
    ---------------------------*/
    const sidebarToggler = $('.sidebar-toggler');
    const sidebarClose = $('.sidebar__close');
    const sidebarArea = $('.sidebar__area');
    //const mainContent = $('.main-content');
    sidebarToggler.on('click', () => {
        sidebarArea.addClass('active');
        //mainContent.toggleClass('active');
    });

    sidebarClose.on('click', ()=>{
        sidebarArea.removeClass('active');
    });

    var alterClass = function() {
        var ww = document.body.clientWidth;
        if (ww > 1199) {
            sidebarArea.removeClass('active');
        }
    };

    $(window).resize(function(){
        alterClass();
    });

    alterClass();

    /*---------------------------
    sidebar menu
    ---------------------------*/
    $('#sidebar-menu').metisMenu();


    /*---------------------------
    file manager page
    ---------------------------*/
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


    /*---------------------------
    blog grid page
    ---------------------------*/
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

    /*---------------------------
    blog list page
    ---------------------------*/
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

    /*---------------------------
    add quantity
    ---------------------------*/
    $(".quantity-btn").on("click", function() {
        var $btnText = $(this);
        var oldValue = $btnText.parent().find("input").val();
        var newValue;
        if ($btnText.text() === "+") {
            newValue = parseInt(oldValue) + 1;
        }
        if($btnText.text() === "-") {
            newValue = parseInt(oldValue) - 1;
            if(newValue == 0){
                newValue = 1;
            }
        }

        $btnText.parent().find("input").val(newValue);
    });

    /*---------------------------
    checkout page
    ---------------------------*/
    const inputCheck = document.querySelectorAll('.input-check');

    Array.from(inputCheck).forEach(function(item){
        item.addEventListener('click', function(){
            if (item.checked == true){
                item.parentElement.parentElement.classList.add('active');
            } else {
                item.parentElement.parentElement.classList.remove('active');
            }
        });
    });

    /*---------------------------
    to do page
    ---------------------------*/
    const todoCheck = document.querySelectorAll('.todo-check');

    Array.from(todoCheck).forEach(function(item){
        item.addEventListener('click', function(){
            if (item.checked == true){
                item.parentElement.parentElement.classList.add('active');
            } else {
                item.parentElement.parentElement.classList.remove('active');
            }
        });
    });

    /*---------------------------
    create new task page
    ---------------------------*/
    $('#task-member').on("focus", function(){
        $('.search-member-list').addClass('active');
    });
    $('#task-member').on("focusout", function(){
        $('.search-member-list').removeClass('active');
    });

    /*---------------------------
    settings page
    ---------------------------*/
    //input checkbox
    const planCheck = document.querySelectorAll('.plan-check input');

    Array.from(planCheck).forEach(function(item){
        item.addEventListener('click', function(){
            if (item.checked == true){
                item.parentElement.parentElement.classList.add('active');
            } else {
                item.parentElement.parentElement.classList.remove('active');
            }
        });
    });

    //card checkbox
    const cardCheck = document.querySelectorAll('.card-check input');

    Array.from(cardCheck).forEach(function(item){
        item.addEventListener('click', function(){
            if (item.checked == true){
                item.parentElement.parentElement.classList.add('active');
            } else {
                item.parentElement.parentElement.classList.remove('active');
            }
        });
    });

})(jQuery);
