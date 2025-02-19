( function ($) {
    // Function to bind the click event for the menu toggle
    function solaceExtraBindSolaceMenuToggle() {
        // Remove any previously attached event handlers to avoid duplicates
        $('.solace-elementor-menu-toggle').off('click').on('click', function() {
            // Toggle the 'elementor-active' class on click
            $(this).toggleClass('elementor-active');
            console.log(222);
            
            // Get the current 'aria-expanded' value and toggle it
            const isExpanded = $(this).attr('aria-expanded') === 'true';
            $(this).attr('aria-expanded', !isExpanded);
        });

        // Open submenu
        $('.elementor-widget-solace-extra-nav-menu .solace-elementor-nav-menu--dropdown .menu-item-has-children > .sub-arrow')
        .off('click').on('click', function(event) {
            // Select the parent element with the class .menu-item-has-children
            var $parentItem = $(this).parent();

            // Close any open submenu on the same level by removing 'active' class from siblings
            $parentItem.siblings('.menu-item-has-children').removeClass('active');
            
            // Toggle 'active' class on the clicked item to open/close the submenu
            $parentItem.toggleClass('active');
        });
    }

    // Initial binding when Elementor frontend is fully initialized
    $(window).on('elementor/frontend/init', function() {
        solaceExtraBindSolaceMenuToggle();
    });

    // Use MutationObserver to monitor DOM changes and rebind events if needed
    const observer = new MutationObserver(() => {
        solaceExtraBindSolaceMenuToggle();
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
    });
   
} )( jQuery );
