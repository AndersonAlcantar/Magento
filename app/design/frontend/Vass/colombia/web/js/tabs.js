class Tab {
    constructor() {
        this.targetTab = document.querySelectorAll(".js-tab-target");
        this.classActive = "is-tab-active";
        this.classContentHide = "is-content-tab-hide";
        this.init();
    }

    init() {
        this.clickToShowTab();
    }

    clickToShowTab() {
        this.targetTab.forEach((targetCurrent) => {
            const listCtaTabs = targetCurrent.querySelectorAll(".js-tab-cta");
            const contentTabs =
                targetCurrent.querySelectorAll(".js-tab-content");

            listCtaTabs.forEach((ctaCurrent) => {
                ctaCurrent.addEventListener("click", () => {
                    const dataCta = ctaCurrent.dataset.tabCta;

                    listCtaTabs.forEach((siblings) => {
                        if (siblings.classList.contains(this.classActive)) {
                            siblings.classList.remove(this.classActive);
                        }
                    });

                    contentTabs.forEach((content) => {
                        const dataContent = content.dataset.tabContent;

                        if (dataContent == dataCta) {
                            content.classList.remove(this.classContentHide);
                        } else {
                            content.classList.add(this.classContentHide);
                        }
                    });

                    ctaCurrent.classList.add(this.classActive);
                });
            });
        });
    }
}

class SubTab {
    constructor() {
        this.targetTab = document.querySelectorAll(".js-subtab-target");
        this.classActive = "is-tab-active";
        this.classContentHide = "is-content-tab-hide";
        this.init();
    }

    init() {
        this.clickToShowTab();
    }

    clickToShowTab() {
        this.targetTab.forEach((targetCurrent) => {
            const listCtaTabs = targetCurrent.querySelectorAll(".js-subtab-cta");
            const contentTabs =
                targetCurrent.querySelectorAll(".js-subtab-content");

            listCtaTabs.forEach((ctaCurrent) => {
                ctaCurrent.addEventListener("click", () => {
                    const dataCta = ctaCurrent.dataset.tabCta;

                    listCtaTabs.forEach((siblings) => {
                        if (siblings.classList.contains(this.classActive)) {
                            siblings.classList.remove(this.classActive);
                        }
                    });

                    contentTabs.forEach((content) => {
                        const dataContent = content.dataset.tabContent;

                        if (dataContent == dataCta) {
                            content.classList.remove(this.classContentHide);
                        } else {
                            content.classList.add(this.classContentHide);
                        }
                    });

                    ctaCurrent.classList.add(this.classActive);
                });
            });
        });
    }
}

new Tab();
new SubTab();
