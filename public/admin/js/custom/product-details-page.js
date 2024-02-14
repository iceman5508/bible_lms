//product details slider
var galleryThumbs = new Swiper('.swiper.product-details__slider__nav', {
    spaceBetween: 10,
    loop: false,
    freeMode: true,
    centeredSlides: true,
    centeredSlidesBounds: true,
    slidesPerView: 4,
    watchOverflow: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
    autoplay: false,
    breakpoints: {
        0: {
            slidesPerView: 3,
        },
        480: {
            slidesPerView: 4,
        }
    }
});
var galleryTop = new Swiper('.swiper.product-details__slider__for', {
    loop: false,
    watchOverflow: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
    preventInteractionOnTransition: true, 
    autoplay: true,
    speed: 2000,
    autoplayDisableOnInteraction: false,
    thumbs: {
        swiper: galleryThumbs,
    },
});