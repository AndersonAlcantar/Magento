class AsideMenuNavigation {
  constructor() {
    this.element = document.querySelector(".js-asidemenu-target");
    this.targetSubmenu = ".js-asidemenu-target-submenu";
    this.targetThirdmenu = ".js-asidemenu-target-thirdmenu";
    this.targetFourthmenu = ".js-asidemenu-target-fourthmenu";

    this.btnWholeMenu = this.element.querySelector(".js-asidemenu-btn-wholemenu");
    this.btnHideWholeMenu = this.element.querySelector(".js-asidemenu-btn-hide-wholemenu");
    this.btnSubMenu = this.element.querySelectorAll(".js-asidemenu-btn-submenu");
    this.btnThirdMenu = this.element.querySelectorAll(".js-asidemenu-btn-thirdmenu");
    this.btnFourthMenu = this.element.querySelectorAll(".js-asidemenu-btn-fourthmenu");

    // Class to show menus
    this.classIsOpenWholeMenu = "is-open" // Show whole menu
    this.classIsOpenSubMenu = "is-open-submenu" // Show sub menu
    this.classIsOpenThirdMenu = "is-open-submenu-third" // Show third menu
    this.classIsOpenFourthMenu = "is-open-submenu-fourth" // Show fourth menu

    this.init()
  }

  init() {
    this.manageToWholeMenu()
    this.clickToHideWholeMenu()
    this.getElementsOfSubMenu()
    this.getElementsOfThirdMenu()
    this.getElementsOfFourthMenu()
  }

  /**
  * Add or remove class to show or hide whole menu
  */
  manageToWholeMenu() {
    this.btnWholeMenu.addEventListener("click", () => {

      // If contain the class return to menu
      if (this.element.classList.contains(this.classIsOpenWholeMenu)) {
        this.returnNavigationOfWholeMenu()

        //  Else not contain, add the class
      } else {
        this.element.classList.add(this.classIsOpenWholeMenu)
        this.addClassActiveOfCurrentMenu()
      }

    })
  }

  /**
  * Remove all class to hide whole menu
  */
  clickToHideWholeMenu() {
    this.btnHideWholeMenu.addEventListener("click", () => {
      this.element.classList.remove(this.classIsOpenWholeMenu)

      this.listOfTargetMenus(
        this.element.querySelectorAll(this.targetSubmenu),
        this.classIsOpenSubMenu
      )

      this.listOfTargetMenus(
        this.element.querySelectorAll(this.targetThirdmenu),
        this.classIsOpenThirdMenu
      )

    })
  }

  /**
  * Arrays to click in the menus, check which element was clicked and remove the active class from the siblings
  * 
  * @param  {string} BtnsSelector ArrayList of buttons
  * @param  {string} targetMenu Target of current item that was clicked
  * @param  {string} classToOpenMenu Class for active the current menu
  * @param  {string} id This indicated if is a sub menu or third menu
  */
  clickToShowMenu(BtnsSelector, targetMenu, classToOpenMenu, id) {
    BtnsSelector.forEach(currentBtn => {

      currentBtn.addEventListener("click", () => {

        var parentCurrentBtn = currentBtn.closest(targetMenu);

        if (parentCurrentBtn.classList.contains(classToOpenMenu)) {

          parentCurrentBtn.classList.remove(classToOpenMenu)
          this.element.classList.remove(id)

        } else {

          BtnsSelector.forEach(siblingsMenu => {

            var parentSiblings = siblingsMenu.closest(targetMenu);

            if (parentSiblings.classList.contains(classToOpenMenu)) {
              siblingsMenu.closest(targetMenu).classList.remove(classToOpenMenu)
            }

          });

          currentBtn.closest(targetMenu).classList.add(classToOpenMenu)
          this.element.classList.add(id)

        }

      })

    });
  }

  /**
  * Toggle class to show or hide sub menu
  */
  getElementsOfSubMenu() {
    this.clickToShowMenu(this.btnSubMenu, this.targetSubmenu, this.classIsOpenSubMenu, 'is-active-submenu')
  }

  /**
  * Toggle class to show or hide third menu
  */
  getElementsOfThirdMenu() {
    this.clickToShowMenu(this.btnThirdMenu, this.targetThirdmenu, this.classIsOpenThirdMenu, 'is-active-thirdmenu')
  }

  /**
  * Toggle class to show or hide fourth menu
  */
  getElementsOfFourthMenu() {
    this.clickToShowMenu(this.btnFourthMenu, this.targetFourthmenu, this.classIsOpenFourthMenu, 'is-active-fourthmenu')
  }

  /**
  * Remove class active in the target menu of item clicked
  * @param  {string} targetElement Class of target element of item clicked
  * @param  {string} classToRemove Class to remove
  */
  listOfTargetMenus(targetElement, classToRemove) {
    targetElement.forEach(element => {
      element.classList.remove(classToRemove)
    });
  }

  /**
  * Validate if is firstmenu section, submenu section or thirdsection to remove it
  */
  returnNavigationOfWholeMenu() {

    if (this.element.classList.contains("is-active-submenu") && !this.element.classList.contains("is-active-thirdmenu")) {

      this.element.classList.remove("is-active-submenu")

      this.listOfTargetMenus(
        this.element.querySelectorAll(this.targetSubmenu),
        this.classIsOpenSubMenu
      )

    } else if (this.element.classList.contains("is-active-thirdmenu") && this.element.classList.contains("is-active-submenu")) {

      this.element.classList.remove("is-active-thirdmenu")

      this.listOfTargetMenus(
        this.element.querySelectorAll(this.targetThirdmenu),
        this.classIsOpenThirdMenu
      )

    } else {
      this.element.classList.remove(this.classIsOpenWholeMenu)
    }

  }

  addClassActiveOfCurrentMenu() {

    if (this.element.querySelector(`.${this.classIsOpenThirdMenu}`)) {
      this.element.classList.add("is-active-thirdmenu")
      this.element.classList.add("is-active-submenu")
    }

  }

}

new AsideMenuNavigation();
