class PlanProduct {
    constructor({ element }) {
        this.element = element;
        this.benefitsList = this.element.querySelector(
            ".js-plan-list-benefits"
        );
        this.elementsToHide = this.element.querySelectorAll(
            ".js-plan-hidde-element"
        );
        this.benefits = this.benefitsList.querySelectorAll(".js-plan-benefit");
        this.toggleBtn = this.element.nextElementSibling;
        this.classHidde = "u-hidden";
        this.classListHide = "is-list-hide";
        this.init();
    }

    init() {
        this.setHeightOfList();
        this.clickIntoToggleBtn();
        this.resizeToOrderElements();
        this.countAndCenterPlans();
        this.clickIntoTabsForAdjustPlans();
    }

    // 1. Set max-height in px for the list
    setHeightOfList() {
        if (this.element) {
            if (window.innerWidth >= 740) {
                if (this.getLengthOfList() > 3) {
                    this.element.style.maxHeight = `${this.getHeightOfList()}px`;
                    this.element.classList.add(this.classListHide);
                    this.hideOrShowToggleButton(true);
                } else {
                    this.hideOrShowToggleButton(false);
                }
            } else {

                if (this.getLengthOfList() == 0) {
                    this.toggleBtn.classList.add(this.classHidde);
                }

                
                this.element.style.maxHeight = 0;
            }
        }
    }

    // 1. Validate if exist more of 3 elements
    // 2. Calculate max-height for the list
    getHeightOfList() {
        let newHeight = 0;

        for (let i = 1; i < this.benefits.length; i++) {
            if (i <= 3) {
                const element = this.benefits[i];
                newHeight = newHeight + element.offsetHeight;
            }
        }

        if (newHeight > 200) {
            newHeight = 170;
        }

        // For hide border bottom of the third element
        return newHeight;
    }

    // 1. Return the number childs of the list
    getLengthOfList() {
        return this.benefitsList.childElementCount;
    }

    // 1. Hide or Show toggle button
    hideOrShowToggleButton(is_dropdown) {
        if (is_dropdown) {
            this.toggleBtn.classList.remove(this.classHidde);
        } else {
            this.toggleBtn.classList.add(this.classHidde);
            this.hideAnotherElements();
        }
    }

    // Display none another elements
    hideAnotherElements() {
        this.elementsToHide.forEach((element) => {
            element.classList.add(this.classHidde);
        });
    }

    // Toggle class list hide
    clickIntoToggleBtn() {
        this.toggleBtn.addEventListener("click", () => {
            if (this.element.classList.contains(this.classListHide)) {
                this.element.classList.remove(this.classListHide);
            } else {
                this.element.classList.add(this.classListHide);
            }
        });
    }

    // Windows resize for order elements in mobile
    resizeToOrderElements() {
        window.addEventListener("resize", () => {
            this.setHeightOfList();

            if (window.innerWidth >= 740 && this.getLengthOfList() <= 3) {
                this.element.style.maxHeight = `fit-content`;
            }

            if (
                window.innerWidth <= 740 &&
                this.toggleBtn.classList.contains(this.classHidde)
            ) {

                if(this.getLengthOfList() == 0 ){
                    this.toggleBtn.classList.add(this.classHidde);
                }
                
            }
        });
    }

    countAndCenterPlans() {
        const wrapListPlans = document.querySelectorAll(
            ".js-plan-product-wrap"
        );

        wrapListPlans.forEach((element) => {
            if (element.childElementCount <= 4) {
                element.classList.add("is-plans-centered");
            }
        });
    }

    clickIntoTabsForAdjustPlans() {
        const listCtaTabs = document.querySelectorAll(".js-tab-cta");

        if (window.innerWidth >= 740) {
            listCtaTabs.forEach((ctaCurrent) => {
                ctaCurrent.addEventListener("click", () => {
                    this.setHeightOfList();
                });
            });
        }
    }
}

const targetListPlan = document.querySelectorAll(".js-plan-target");

targetListPlan.forEach((item) => {
    new PlanProduct({
        element: item,
    });
});
