import Swiper from "https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.1.5/swiper-bundle.esm.browser.min.js";
// import Swiper from "https://unpkg.com/swiper@8/swiper-bundle.esm.browser.min.js";
const swiperBanner = new Swiper(".js-swiper-banner", {
    slidesPerView: "auto",
    spaceBetween: 16,

    // Navigation arrows
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

const createSwiperProduct = function() {
    const targetSliderProducts = document.querySelector(".js-swiper-cards-product");

    if(targetSliderProducts) {
        const wrappSlider = targetSliderProducts.closest(".js-slider-product-wrap");

        new Swiper(targetSliderProducts, {
            slidesPerView: 1,
            
            navigation: {
                nextEl: wrappSlider.querySelector(".swiper-button-next"),
                prevEl: wrappSlider.querySelector(".swiper-button-prev"),
            },
            
            breakpoints: {
                740: {
                    slidesPerView: 2,
                    spaceBetween: 24,

                    // Navigation arrows
                    navigation: {
                        nextEl: wrappSlider.querySelector(".swiper-button-next"),
                        prevEl: wrappSlider.querySelector(".swiper-button-prev"),
                    },
                },
                
                1023: {
                    slidesPerView: 4,
                    spaceBetween: 40,

                    // Navigation arrows
                    navigation: {
                        nextEl: wrappSlider.querySelector(".swiper-button-next"),
                        prevEl: wrappSlider.querySelector(".swiper-button-prev"),
                    },
                },
            },
        });
    }

}

var swiperCaracterizticas;
const CreateSwiperCaracterizticas = function () {
    const targetSlider = document.querySelector(".js-slider-caracterizticas");

    if (targetSlider) {
        swiperCaracterizticas = new Swiper(targetSlider, {
            slidesPerView: "auto",
            spaceBetween: 24,
        });

        if (window.innerWidth > 1024) {
            swiperCaracterizticas.destroy(true, true);
        }
    }
};

var swiperPlanProduct;
const CreateSwiperPlanProduct = function () {
    const targetSlider = document.querySelectorAll(".js-slider-plan-product");

    if (targetSlider) {
        targetSlider.forEach((element) => {
            const wrappSlider = element.closest(".js-slider-plan-product-wrap");

            swiperPlanProduct = new Swiper(element, {
                slidesPerView: "auto",
                spaceBetween: 24,
                clickable: false,
                followFinger: false,

                breakpoints: {
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 24,
                        clickable: false,
                        followFinger: false,
                    },
                },

                navigation: {
                    nextEl: wrappSlider.querySelector(".swiper-button-next"),
                    prevEl: wrappSlider.querySelector(".swiper-button-prev"),
                },
            });

            if (window.innerWidth < 740) {
                swiperPlanProduct.destroy(true, true);
            }
        });
    }
};

var swiperServices;
const CreateSwiperServices = function () {
    const targetSlider = document.querySelectorAll(".js-slider-services");

    if (targetSlider) {
        targetSlider.forEach((element) => {
            const wrappSlider = element.closest(".js-slider-plan-product-wrap");

            swiperServices = new Swiper(element, {
                slidesPerView: "auto",
                spaceBetween: 16,
                followFinger: false,
                allowTouchMove: false,

                breakpoints: {
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 16,
                        clickable: false,
                        followFinger: false,
                    },
                },

                navigation: {
                    nextEl: wrappSlider.querySelector(".swiper-button-next"),
                    prevEl: wrappSlider.querySelector(".swiper-button-prev"),
                },

                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },

                on: {
                    init: function () {
                        const element = this.el.querySelector(".swiper-wrapper")

                        if (element.childElementCount <= 4) {
                            element.closest(
                                ".js-slider-services"
                            ).classList.add("is-centered");
                        } else if (element.childElementCount > 5) {
                            element.closest(
                                ".js-slider-services"
                            ).classList.add("is-swiper");
                        }
                    },
                },
            });

            if (window.innerWidth < 740) {
                swiperServices.destroy(true, true);
            }
        });
    }
};

const swiperBenefits = new Swiper(".js-swiper-benefits", {
    slidesPerView: 1,

    breakpoints: {
        740: {
            slidesPerView: "auto",
            spaceBetween: 16,
        },
        1023: {
            slidesPerView: 5,
            spaceBetween: 16,
        },
    },

    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    on: {
        init: function () {
            const element = this.el.querySelector(".swiper-wrapper")

            if (element.childElementCount <= 4) {
                element.closest(
                    ".js-swiper-benefits "
                ).classList.add("is-centered");
            } else if (element.childElementCount > 5) {
                element.closest(
                    ".js-swiper-benefits "
                ).classList.add("is-swiper");
            }
        },
    },

});

createSwiperProduct();
CreateSwiperCaracterizticas();
CreateSwiperPlanProduct();
CreateSwiperServices();

window.addEventListener("resize", () => {
    if (swiperCaracterizticas) {
        if (!swiperCaracterizticas.destroyed && window.innerWidth >= 1024) {
            swiperCaracterizticas.destroy(true, true);
        } else if (swiperCaracterizticas.destroyed) {
            CreateSwiperCaracterizticas();
        }
    }

    if (swiperPlanProduct) {
        if (!swiperPlanProduct.destroyed && window.innerWidth <= 739) {
            swiperPlanProduct.destroy(true, true);
        } else if (swiperPlanProduct.destroyed) {
            CreateSwiperPlanProduct();
        }
    }

    if (swiperServices) {
        if (!swiperServices.destroyed && window.innerWidth <= 739) {
            swiperServices.destroy(true, true);
        } else if (swiperServices.destroyed) {
            CreateSwiperServices();
        }
    }
});
