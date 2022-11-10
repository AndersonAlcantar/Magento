class Dropdown {
    constructor() {
        this.classTargetDropdown = "js-target-dropdown";
        this.btnsDropdown = document.querySelectorAll(".js-btn-dropdown");
        this.classActiveMenuDropdown = "is-dropdown-active";
        this.init();
    }

    init() {
        this.clickToOpenMenu();
        this.mouseOut();
    }

    clickToOpenMenu() {
        this.btnsDropdown.forEach((currentBtn) => {
            currentBtn.addEventListener("click", () => {
                const targetDropdown = currentBtn.closest(
                    "." + this.classTargetDropdown
                );

                if (
                    targetDropdown.classList.contains(
                        this.classActiveMenuDropdown
                    )
                ) {
                    targetDropdown.classList.remove(
                        this.classActiveMenuDropdown
                    );
                } else {
                    this.btnsDropdown.forEach((siblingsBtnCurrent) => {
                        const listDropdownParent = siblingsBtnCurrent.closest(
                            "." + this.classTargetDropdown
                        );

                        if (
                            !listDropdownParent.classList.contains(
                                "c-filter__list"
                            ) &&
                            !listDropdownParent.classList.contains(
                                "c-form__fieldset"
                            )
                        ) {
                            if (listDropdownParent) {
                                if (
                                    listDropdownParent.classList.contains(
                                        this.classActiveMenuDropdown
                                    )
                                ) {
                                    listDropdownParent.classList.remove(
                                        this.classActiveMenuDropdown
                                    );
                                }
                            }
                        }
                    });

                    targetDropdown.classList.add(this.classActiveMenuDropdown);
                }
            });
        });
    }

    mouseOut() {
        window.addEventListener("click", (e) => {
            if (!e.target.classList.contains("js-btn-dropdown")) {
                const htmlClassDropdowns = document.querySelectorAll(
                    "." + this.classTargetDropdown
                );

                htmlClassDropdowns.forEach((dropdown) => {
                    if (
                        !dropdown.classList.contains("c-filter__list") &&
                        !dropdown.classList.contains("c-form__fieldset")
                    ) {
                        dropdown.classList.remove(this.classActiveMenuDropdown);
                    }
                });
            }
        });
    }
}

new Dropdown();

// this is only for capacity in product detail
function dropdownCapacidad() {
    const parentDropdown = document.querySelector(
        "[data-attribute-code='memoria_interna capacidad']"
    );
    const classActive = "is-dropdown-capacity-active";

    if (parentDropdown) {
        const btnDropdown = parentDropdown.querySelector(
            ".swatch-attribute-selected-option"
        );
        const itemsDropdown = parentDropdown.querySelectorAll(
            ".swatch-option.text"
        );

        if (btnDropdown) {
            btnDropdown.addEventListener("click", () => {
                parentDropdown.classList.toggle(classActive);
            });

            itemsDropdown.forEach((item) => {
                item.addEventListener("click", () => {
                    parentDropdown.classList.remove(classActive);
                });
            });

            return 1;
        }
    } else {
        return 0;
    }
}

const interbalDropdown = setInterval(dropdownCapacidad, 1000);

const interbalClearInterval = setInterval(() => {
    dropdownCapacidad();

    if (dropdownCapacidad() > 0) {
        clearInterval(interbalDropdown);
        clearInterval(this);
        clearInterval(interbalClearInterval);
    }
}, 1000);

function dropdownSumary() {
    // Elementos del DOM
    const btnDropdown = document.querySelector(".js-summary-btn");
    const contentDropdown = document.querySelector(".js-target-summary");
    const classActive = "is-summary-show";

    if (contentDropdown && btnDropdown) {
        btnDropdown.addEventListener("click", () => {
            // Agregar o Remover clases active al boton y contenido
            btnDropdown.classList.toggle(classActive);
            contentDropdown.classList.toggle(classActive);
        });

        return 1;
    } else {
        return 0;
    }
}

const intervalDropdownSummary = setInterval(dropdownSumary, 1000);

const interbalClearIntervalSummary = setInterval(() => {
    dropdownSumary();

    if (dropdownSumary() > 0) {
        clearInterval(intervalDropdownSummary);
        clearInterval(this);
        clearInterval(interbalClearIntervalSummary);
    }
}, 1000);
