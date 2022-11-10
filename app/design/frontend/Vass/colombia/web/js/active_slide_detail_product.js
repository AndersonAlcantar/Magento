if (window.innerWidth < 768) {
    function setDataSliderAttr() {
        const itemThumb = document.querySelectorAll(
            ".fotorama__nav__frame.fotorama__nav__frame--thumb"
        );

        const itemDot = document.querySelectorAll(
            ".fotorama__nav__frame.fotorama__nav__frame--dot"
        );

        if (itemThumb.length > 0 && itemDot.length > 0) {
            for (let i = 0; i < itemThumb.length; i++) {
                const elementThumb = itemThumb[i];
                elementThumb.setAttribute("data-slider-thumb", "slide_" + i);
            }

            for (let i = 0; i < itemDot.length; i++) {
                const elementDot = itemDot[i];
                elementDot.setAttribute("data-slider-thumb", "slide_" + i);
            }

            return itemDot.length;
        } else {
            return 0;
        }
    }

    function clickToActiveSlide() {
        const itemThumb = document.querySelectorAll(
            ".fotorama__nav__frame.fotorama__nav__frame--thumb"
        );

        const itemDot = document.querySelectorAll(
            ".fotorama__nav__frame.fotorama__nav__frame--dot"
        );

        itemThumb.forEach((current) => {
            current.addEventListener("click", (e) => {
                for (let i = 0; i < itemThumb.length; i++) {
                    for (let i = 0; i < itemDot.length; i++) {
                        if (itemDot[i].getAttribute("data-active") == "true") {
                            if (
                                itemDot[i].getAttribute("data-slider-thumb") ==
                                itemThumb[i].getAttribute("data-slider-thumb")
                            ) {
                                itemThumb[i].classList.add("fotorama__active");
                            }
                        } else {
                            itemThumb[i].classList.remove("fotorama__active");
                        }
                    }
                }
            });
        });
    }

    const interbalDataSlide = setInterval(setDataSliderAttr, 1000);

    const interbalClearInterval = setInterval(() => {
        setDataSliderAttr();

        if (setDataSliderAttr() > 0) {
            clearInterval(interbalDataSlide);
            clearInterval(this);
            clearInterval(interbalClearInterval);

            clickToActiveSlide();
        }
    }, 1000);
}
