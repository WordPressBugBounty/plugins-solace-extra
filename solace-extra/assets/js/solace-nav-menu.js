( function ($) {
    // Helper function to set CSS with !important
    function setStyleImportant($element, property, value) {
        const element = $element[0];
        if (element && element.style) {
            element.style.setProperty(property, value, 'important');
        }
    }

    // Helper function to remove CSS property
    function removeStyleProperty($element, property) {
        const element = $element[0];
        if (element && element.style) {
            element.style.removeProperty(property);
        }
    }

    // Function to bind hover event for menu items with children (min 1024px)
    function solaceExtraBindHoverActive() {
        // Check if window width is at least 1024px
        function isMinWidth1024() {
            return $(window).width() >= 1024;
        }

        // Remove any previously attached event handlers to avoid duplicates
        $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal li.menu-item-has-children, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical li.menu-item-has-children').off('mouseenter.hoverActive mouseleave.hoverActive');
        $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal ul.sub-menu, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical ul.sub-menu').off('mouseenter.subMenuHover mouseleave.subMenuHover');

        // Only bind if window width is at least 1024px
        if (isMinWidth1024()) {
            // Hover event untuk li.menu-item-has-children
            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal li.menu-item-has-children, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical li.menu-item-has-children').on('mouseenter.hoverActive', function() {
                const $menuItem = $(this);
                const $subMenu = $menuItem.find('ul.sub-menu').first();
                
                // Tutup semua submenu dari menu item lain yang sedang terbuka
                $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal li.menu-item-has-children, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical li.menu-item-has-children').not($menuItem).each(function() {
                    const $otherMenuItem = $(this);
                    const $otherSubMenu = $otherMenuItem.find('ul.sub-menu').first();
                    
                    // Clear timeout dari menu item lain
                    if ($otherMenuItem.data('hover-timeout')) {
                        clearTimeout($otherMenuItem.data('hover-timeout'));
                        $otherMenuItem.removeData('hover-timeout');
                    }
                    
                    // Reset flag dan tutup submenu
                    $otherMenuItem.data('li-hovering', false);
                    $otherSubMenu.data('submenu-hovering', false);
                    removeStyleProperty($otherSubMenu, 'display');
                });
                
                // Clear any existing timeout
                if ($menuItem.data('hover-timeout')) {
                    clearTimeout($menuItem.data('hover-timeout'));
                    $menuItem.removeData('hover-timeout');
                }

                // Jika sub-menu sedang di-hover, jangan lakukan apa-apa
                if ($subMenu.data('submenu-hovering')) {
                    return;
                }

                // Tambahkan display block !important ke sub-menu
                setStyleImportant($subMenu, 'display', 'block');
                $menuItem.data('li-hovering', true);
            });

            // Mouse leave event untuk li.menu-item-has-children
            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal li.menu-item-has-children, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical li.menu-item-has-children').on('mouseleave.hoverActive', function() {
                const $menuItem = $(this);
                const $subMenu = $menuItem.find('ul.sub-menu').first();
                
                // Clear any existing timeout
                if ($menuItem.data('hover-timeout')) {
                    clearTimeout($menuItem.data('hover-timeout'));
                }

                // Jika sub-menu sedang di-hover, jangan hapus display block
                if ($subMenu.data('submenu-hovering')) {
                    $menuItem.data('li-hovering', false);
                    return;
                }

                // Set flag bahwa li tidak lagi di-hover
                $menuItem.data('li-hovering', false);

                // Tahan selama 200 milidetik sebelum menghapus display block
                const timeoutId = setTimeout(function() {
                    // Cek lagi apakah sub-menu sedang di-hover
                    if (!$subMenu.data('submenu-hovering') && !$menuItem.data('li-hovering')) {
                        removeStyleProperty($subMenu, 'display');
                    }
                    $menuItem.removeData('hover-timeout');
                }, 200);

                // Store timeout ID in data attribute
                $menuItem.data('hover-timeout', timeoutId);
            });

            // Hover event untuk ul.sub-menu
            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal ul.sub-menu, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical ul.sub-menu').on('mouseenter.subMenuHover', function() {
                const $subMenu = $(this);
                const $menuItem = $subMenu.closest('li.menu-item-has-children');
                
                // Set flag bahwa sub-menu sedang di-hover
                $subMenu.data('submenu-hovering', true);
                
                // Clear timeout dari li jika ada
                if ($menuItem.data('hover-timeout')) {
                    clearTimeout($menuItem.data('hover-timeout'));
                    $menuItem.removeData('hover-timeout');
                }

                // Pastikan display block tetap ada
                setStyleImportant($subMenu, 'display', 'block');
            });

            // Mouse leave event untuk ul.sub-menu
            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal ul.sub-menu, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical ul.sub-menu').on('mouseleave.subMenuHover', function() {
                const $subMenu = $(this);
                const $menuItem = $subMenu.closest('li.menu-item-has-children');
                
                // Set flag bahwa sub-menu tidak lagi di-hover
                $subMenu.data('submenu-hovering', false);

                // Jika li juga tidak di-hover, hapus display block setelah 200 milidetik
                if (!$menuItem.data('li-hovering')) {
                    const timeoutId = setTimeout(function() {
                        // Cek lagi apakah sub-menu atau li sedang di-hover
                        if (!$subMenu.data('submenu-hovering') && !$menuItem.data('li-hovering')) {
                            removeStyleProperty($subMenu, 'display');
                        }
                        $menuItem.removeData('hover-timeout');
                    }, 200);
                    
                    $menuItem.data('hover-timeout', timeoutId);
                }
            });
        } else {
            // Jika resolusi < 1024px, hapus semua style inline yang ditambahkan
            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-horizontal ul.sub-menu, .elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical ul.sub-menu').each(function() {
                const $subMenu = $(this);
                removeStyleProperty($subMenu, 'display');
            });
        }
    }

    // Function to bind the click event for the menu toggle
    function solaceExtraBindSolaceMenuToggle() {
        $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical .menu-item-has-children').on('mouseenter', function() {
            var $menuItem = $(this);
            var $extraLink = $menuItem.find('a.extra-link').first();
            const $subMenu = $menuItem.find('.sub-menu').first();
            var extraLinkWidth = $extraLink.outerWidth();
        
            $menuItem.find('.sub-menu').css('left', extraLinkWidth + 'px');

            $('.elementor-widget-solace-extra-nav-menu .elementor-nav-menu--layout-vertical .menu-item-has-children .sub-menu').removeClass('active');

            // Add 'active' class, then remove it after 200 milliseconds
            $subMenu.addClass('active');

            setTimeout(() => {
                $subMenu.removeClass('active');
            }, 200);            
        });

        // Remove any previously attached event handlers to avoid duplicates
        $('.solace-elementor-menu-toggle').off('click').on('click', function() {
            const $toggle = $(this);
            const isCurrentlyActive = $toggle.hasClass('elementor-active');

            // Get position before toggling class
            // If not active, get current position. If active, use saved original position or get current
            let topPosition, leftPosition;
            
            if (!isCurrentlyActive) {
                // Save original position before making it fixed
                // Get position from icon (svg or i) inside the toggle
                const $icon = $toggle.find('svg, i').first();
                const iconOffset = $icon.length ? $icon.offset() : $toggle.offset();
                topPosition = iconOffset.top;
                leftPosition = iconOffset.left;
                
                // Store original position in data attribute for future use
                $toggle.data('original-top', topPosition);
                $toggle.data('original-left', leftPosition);
            } else {
                // Use saved original position if available
                topPosition = $toggle.data('original-top');
                leftPosition = $toggle.data('original-left');
            }

            // Toggle the 'elementor-active' class on click
            $toggle.toggleClass('elementor-active');
            
            // Apply top and left position when active
            if ($toggle.hasClass('elementor-active') && topPosition !== undefined && leftPosition !== undefined) {
                $toggle.css({
                    'top': topPosition - 3 + 'px',
                    'left': leftPosition - 3 + 'px'
                });
            } else {
                // Reset position when inactive
                $toggle.css({
                    'top': '',
                    'left': ''
                });
            }
            
            // Get the current 'aria-expanded' value and toggle it
            const isExpanded = $toggle.attr('aria-expanded') === 'true';
            $toggle.attr('aria-expanded', !isExpanded);

            // Atur lebar dan posisi dropdown nav ketika menu dibuka
            // Cari nav dropdown di dalam widget yang sama
            const $widget   = $toggle.closest('.elementor-widget-solace-extra-nav-menu');
            const $dropdown = $widget.find('.elementor-nav-menu--dropdown.solace-elementor-nav-menu--dropdown.elementor-nav-menu__container');

            // Tambahkan class khusus untuk state terbuka (beda dari class active)
            $dropdown.toggleClass('solace-dropdown-open');
            
            // Set padding-top dari topPosition saat dropdown terbuka
            if ($dropdown.length) {
                if ($dropdown.hasClass('solace-dropdown-open') && topPosition !== undefined) {
                    $dropdown.css({
                        'paddingTop': topPosition + 55 + 'px'
                    });
                } else {
                    // Reset padding-top saat dropdown ditutup
                    $dropdown.css({
                        'paddingTop': '115px'
                    });
                }
            }

            // Toggle display none untuk #wpadminbar
            const $wpAdminBar = $('#wpadminbar');
            if ($wpAdminBar.length) {
                if ($toggle.hasClass('elementor-active')) {
                    $wpAdminBar.css('display', 'none');
                } else {
                    $wpAdminBar.css('display', '');
                }
            }
        });

        // Attach a click event handler to links (<a>) that are direct children of <li> elements 
        $('.elementor-nav-menu--dropdown.solace-elementor-nav-menu--dropdown li.menu-item-has-children > a').on('click', function(e) {
            const $link = $(this);
            const $submenu = $link.siblings('.sub-menu').css({
                'display': 'block',
                'width': '100%'
            });

            // If the submenu does NOT have the 'active' class yet (first click)
            if (!$submenu.hasClass('active')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                // Add 'active' class and set CSS again to make sure it's visible and full width
                $submenu.addClass('active').css({
                    'display': 'block',
                    'width': '100%'
                });
            }
        });
    }

    // Initial binding when Elementor frontend is fully initialized
    $(window).on('elementor/frontend/init', function() {
        solaceExtraBindSolaceMenuToggle();
        solaceExtraBindHoverActive();
    });

    // Use MutationObserver to monitor DOM changes and rebind events if needed
    const observer = new MutationObserver(() => {
        solaceExtraBindSolaceMenuToggle();
        solaceExtraBindHoverActive();
    });

    // Start observing the body for child list and subtree changes
    observer.observe(document.body, {
        childList: true,  // Observe direct children
        subtree: true,    // Observe all descendants
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var delayedElements = document.querySelectorAll('.delayed-content');
            delayedElements.forEach(function(el) {
                el.classList.add('show');
            });
        }, 1000); // Delay 1 detik
        
        // Bind hover active on DOM ready
        solaceExtraBindHoverActive();
    });

    // Rebind hover active on window resize
    let resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            solaceExtraBindHoverActive();
        }, 250); // Debounce resize event
    });
   
} )( jQuery );
