var swiper = new Swiper(".bundle-all-slider", {
    direction: "vertical",
    slidesPerView: 2,
    loop: false,
    pagination: {
        el: ".swiper-pagination",
        type: "progressbar",
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});
