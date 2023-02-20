/**
 * Main
 */

'use strict';

let menu,
    animate;

(function () {
    if (typeof Waves !== 'undefined') {
        Waves.init();
        Waves.attach(".btn[class*='btn-']:not([class*='btn-outline-']):not([class*='btn-label-'])", ['waves-light']);
        Waves.attach("[class*='btn-outline-']");
        Waves.attach("[class*='btn-label-']");
        Waves.attach('.pagination .page-item .page-link');
    }

    // Initialize menu
    //-----------------

    let layoutMenuEl = document.querySelectorAll('#layout-menu');
    layoutMenuEl.forEach(function (element) {
        menu = new Menu(element, {
            orientation: 'vertical',
            closeChildren: false,
        });
        // Change parameter to true if you want scroll animation
        window.Helpers.scrollToActive((animate = false));
        window.Helpers.mainMenu = menu;
    });

    // Initialize menu togglers and bind click on each
    let menuToggler = document.querySelectorAll('.layout-menu-toggle');
    menuToggler.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
            // Enable menu state with local storage support if enableMenuLocalStorage = true from config.js
            if (!window.Helpers.isSmallScreen()) {
                try {
                    localStorage.setItem(
                        'templateCustomizer-' + templateName + '--LayoutCollapsed',
                        String(window.Helpers.isCollapsed())
                    );
                } catch (e) {
                }
            }
        });
    });

    // Menu swipe gesture

    // Detect swipe gesture on the target element and call swipe In
    window.Helpers.swipeIn('.drag-target', function (e) {
        window.Helpers.setCollapsed(false);
    });

    // Detect swipe gesture on the target element and call swipe Out
    window.Helpers.swipeOut('#layout-menu', function (e) {
        if (window.Helpers.isSmallScreen()) window.Helpers.setCollapsed(true);
    });

    // Display in main menu when menu scrolls
    let menuInnerContainer = document.getElementsByClassName('menu-inner'),
        menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
    if (menuInnerContainer.length > 0 && menuInnerShadow) {
        menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
            if (this.querySelector('.ps__thumb-y').offsetTop) {
                menuInnerShadow.style.display = 'block';
            } else {
                menuInnerShadow.style.display = 'none';
            }
        });
    }

    // Init helpers & misc
    // --------------------

    // Init BS Tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    })


    // Auto update layout based on screen size
    window.Helpers.setAutoUpdate(true);

    // Toggle Password Visibility
    window.Helpers.initPasswordToggle();

    // Speech To Text
    window.Helpers.initSpeechToText();

    // Init PerfectScrollbar in Navbar Dropdown (i.e notification)
    window.Helpers.initNavbarDropdownScrollbar();

    // On window resize listener
    // -------------------------
    window.addEventListener(
        'resize',
        function (event) {
            // Hide open search input and set value blank
            if (window.innerWidth >= window.Helpers.LAYOUT_BREAKPOINT) {
                if (document.querySelector('.search-input-wrapper')) {
                    document.querySelector('.search-input-wrapper').classList.add('d-none');
                    document.querySelector('.search-input').value = '';
                }
            }
        },
        true
    );

    // Manage menu expanded/collapsed with templateCustomizer & local storage
    //------------------------------------------------------------------

    // If current layout is vertical and current window screen is > small

    // Auto update menu collapsed/expanded based on the themeConfig
    if (typeof TemplateCustomizer !== 'undefined') {
        if (window.templateCustomizer.settings.defaultMenuCollapsed) {
            window.Helpers.setCollapsed(true, false);
        }
    }
})();
