/**
 * Mobile Menu Toggle
 * Handles hamburger menu interaction with proper ARIA attributes
 */

(function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');

    if (!menuToggle || !menu) return;

    // Set initial aria-hidden state
    menu.setAttribute('aria-hidden', 'true');

    menuToggle.addEventListener('click', function() {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

        // Toggle ARIA attributes
        menuToggle.setAttribute('aria-expanded', !isExpanded);
        menu.setAttribute('aria-hidden', isExpanded);
    });
})();
