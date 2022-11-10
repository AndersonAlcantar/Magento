function activePlayVideoGalery() {
    const itemsThumbs = document.querySelectorAll(
        ".fotorama__nav__frame.video-thumb-icon"
    );

    if (itemsThumbs.length > 0) {
        itemsThumbs.forEach((item) => {
            item.addEventListener("click", () => {
                setTimeout(() => {
                    const containerVideo = document.querySelector(
                        ".fotorama-video-container.fotorama__active"
                    );

                    containerVideo.click();
                    containerVideo.classList.add("is-video-load");

                    const intervalClearIntervalLoader = setInterval(() => {
                        if (
                            containerVideo.classList.contains(
                                "fotorama__product-video--loaded"
                            )
                        ) {
                            containerVideo.classList.remove("is-video-load");
                            clearInterval(intervalClearIntervalLoader);
                        }
                    }, 1000);
                }, 1500);
            });
        });

        return 1;
    } else {
        return 0;
    }
}

function activePlayVideoGaleryArrow() {
    const itemsThumbs = document.querySelectorAll(".fotorama__arr");

    if (itemsThumbs.length > 0) {
        itemsThumbs.forEach((item) => {
            item.addEventListener("click", () => {
                setTimeout(() => {
                    const containerVideo = document.querySelector(
                        ".fotorama-video-container.fotorama__active"
                    );

                    if (containerVideo) {
                        containerVideo.click();
                        containerVideo.classList.add("is-video-load");

                        const intervalClearIntervalLoader = setInterval(() => {
                            if (
                                containerVideo.classList.contains(
                                    "fotorama__product-video--loaded"
                                )
                            ) {
                                containerVideo.classList.remove(
                                    "is-video-load"
                                );
                                clearInterval(intervalClearIntervalLoader);
                            }
                        }, 1000);
                    }
                }, 1500);
            });
        });

        return 1;
    } else {
        return 0;
    }
}

const intervalActivePlayVideo = setInterval(activePlayVideoGalery, 1000);

const intervalClearInterval = setInterval(() => {
    activePlayVideoGalery();
    if (activePlayVideoGalery() > 0) {
        clearInterval(intervalActivePlayVideo);
        clearInterval(this);
        clearInterval(intervalClearInterval);
    }
}, 1000);

const intervalActivePlayVideoArrow = setInterval(
    activePlayVideoGaleryArrow,
    1000
);

const intervalClearIntervalArrow = setInterval(() => {
    activePlayVideoGaleryArrow();
    if (activePlayVideoGaleryArrow() > 0) {
        clearInterval(intervalActivePlayVideoArrow);
        clearInterval(this);
        clearInterval(intervalClearIntervalArrow);
    }
}, 1000);
