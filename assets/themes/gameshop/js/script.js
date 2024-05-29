// pre loader
const preloader = document.getElementById("preloader");
window.addEventListener("load", function () {
    preloader.style.cssText = `opacity: 0; visibility: hidden;`;
});

window.addEventListener("scroll", function () {
    let scrollpos = window.scrollY;
    const header = document.querySelector("nav");
    const headerHeight = header.offsetHeight;

    if (scrollpos >= headerHeight) {
        header.classList.add("active");
    } else {
        header.classList.remove("active");
    }
});

window.onload = function () {
    const descriptionBtn = document.getElementById("descriptionBtn");
    const instructionBtn = document.getElementById("instructionBtn");
    const reviewBtn = document.getElementById("reviewBtn");

    const description = document.getElementById("description");
    const instruction = document.getElementById("instruction");
    const reviews = document.getElementById("reviews");

};

// package details navigator
const navigations = document.getElementsByClassName("navigate");
for (const element of navigations) {
    element.addEventListener("click", () => {
        for (const ele of navigations) {
            ele.classList.remove("active");
        }
        element.classList.add("active");
    });
}

const previewImage = (id) => {
    document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

// jquery code
$(document).ready(function () {
    // SCROLL TOP
    $(".scroll-top").fadeOut();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $(".scroll-top").fadeIn();
        } else {
            $(".scroll-top").fadeOut();
        }
    });


    $(".nav-item > a").on('click',function () {
        $(".nav-item > a").removeClass("active");
        $(this).addClass("active");
    });

    // SKITTER SLIDER
    $(function () {
        $(".skitter-large").skitter({
            velocity: 2.0,
            interval: 4000,
            fullscreen: true,
            dots: false,
        });
    });

    // OWL CAROUSEL
    $(".testimonials").owlCarousel({
        smartSpeed: 500,
        merge: true,
        loop: true,
        nav: true,
        margin: 0,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        responsive: {
            0: {
                items: 1,
            },
            992: {
                items: 2,
            },
        },
    });

    $(".image-slider").owlCarousel({
        smartSpeed: 500,
        merge: true,
        loop: true,
        nav: false,
        margin: 0,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        responsive: {
            0: {
                items: 1,
            },
            450: {
                items: 1,
            },
        },
    });

    // AOS ANIMATION
    AOS.init();

    // COUNTER UP
    $(".counter").counterUp({
        delay: 10,
        time: 2000,
    });

});
