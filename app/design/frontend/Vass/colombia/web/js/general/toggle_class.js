/* Project specific Javascript goes here. */
class AddRemoveClass {
  constructor({ element }) {
    this.element = element;
    this.parentElement = this.element.closest(".js-target-class");
    this.callToRemoveClass = this.parentElement.querySelector(
      ".js-btn-remove-class"
    );
    this.classToAdd = this.element.dataset.classIs;
    this.init();
  }

  init() {
    this.clickEventToAddClass();
    this.clickEventToRemoveClass();
  }

  /**
   * Add class of attr dataset class-is
   */
  clickEventToAddClass() {
    this.element.addEventListener("click", () => {
      this.parentElement.classList.add(this.classToAdd);
    });
  }

  /**
   * Remove parent class if it contains it
   */
  clickEventToRemoveClass() {
    this.callToRemoveClass.addEventListener("click", () => {
      if (this.parentElement.classList.contains(this.classToAdd)) {
        this.parentElement.classList.remove(this.classToAdd);
      }
    });
  }
}

class toggleClass {
  constructor({ element }) {
    this.element = element;
    this.parentElement = this.element.closest(".js-target-class");
    this.classToToggle = this.element.dataset.classIs;
    this.init();
  }

  init() {
    this.toggleEventToChangeClass();
  }

  /**
   * Add or Remove class of attr dataset class-is
   */
  toggleEventToChangeClass() {
    this.element.addEventListener("click", () => {
      if (this.parentElement) {
        this.parentElement.classList.toggle(this.classToToggle);
      }
    });
  }
}

/**
 * All elements js-btn-add-class of DOM
 */
const callToAddClass = document.querySelectorAll(".js-btn-add-class");

/**
 * All elements js-btn-toggle-class of DOM
 */
const callToToggleClass = document.querySelectorAll(".js-btn-toggle-class");

callToAddClass.forEach((item) => {
  new AddRemoveClass({
    element: item,
  });
});

callToToggleClass.forEach((item) => {
  new toggleClass({
    element: item,
  });
});
