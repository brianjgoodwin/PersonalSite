/**
 * Mobile Menu Toggle
 * Handles hamburger menu interaction with proper ARIA attributes,
 * focus management, keyboard navigation, and screen reader announcements
 */

(function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');
    const menuStatus = document.getElementById('menu-status');

    if (!menuToggle || !menu) return;

    // Set initial aria-hidden state
    menu.setAttribute('aria-hidden', 'true');

    // Get all focusable elements within the menu
    const getFocusableElements = () => {
        return menu.querySelectorAll(
            'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
        );
    };

    // Focus trap - keep focus within menu when open
    const trapFocus = (e) => {
        if (e.key !== 'Tab') return;

        const focusableElements = getFocusableElements();
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstFocusable) {
            // Shift+Tab on first element - go to last
            lastFocusable.focus();
            e.preventDefault();
        } else if (!e.shiftKey && document.activeElement === lastFocusable) {
            // Tab on last element - go to first
            firstFocusable.focus();
            e.preventDefault();
        }
    };

    // Toggle menu open/closed
    const toggleMenu = (shouldOpen) => {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        const willOpen = shouldOpen !== undefined ? shouldOpen : !isExpanded;

        // Toggle ARIA attributes
        menuToggle.setAttribute('aria-expanded', willOpen);
        menu.setAttribute('aria-hidden', !willOpen);

        if (willOpen) {
            // Menu is opening
            // Focus first link in menu
            const firstLink = menu.querySelector('a');
            if (firstLink) {
                // Small delay to allow CSS transition to start
                setTimeout(() => firstLink.focus(), 50);
            }

            // Enable focus trap
            menu.addEventListener('keydown', trapFocus);

            // Announce to screen readers
            if (menuStatus) {
                menuStatus.textContent = 'Navigation menu opened';
            }
        } else {
            // Menu is closing
            // Disable focus trap
            menu.removeEventListener('keydown', trapFocus);

            // Return focus to toggle button
            menuToggle.focus();

            // Announce to screen readers
            if (menuStatus) {
                menuStatus.textContent = 'Navigation menu closed';
            }
        }
    };

    // Click handler
    menuToggle.addEventListener('click', () => toggleMenu());

    // ESC key handler - close menu
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.getAttribute('aria-hidden') === 'false') {
            toggleMenu(false); // Close menu
        }
    });
})();
