/**
 * Dynamic Breadcrumb Navigation
 * Tracks user navigation path and updates breadcrumbs for context-sensitive pages
 */

(function() {
    'use strict';

    const STORAGE_KEY = 'nav-referrer';
    const PRIVACY_PATH = '/privacy';

    /**
     * Store current page info before navigating away
     */
    const trackNavigation = () => {
        // Track clicks on all internal links
        document.querySelectorAll('a[href]').forEach(link => {
            // Only track internal links (same origin)
            if (link.origin !== window.location.origin) return;

            link.addEventListener('click', function(e) {
                // Don't track if it's the same page
                if (link.pathname === window.location.pathname) return;

                // Store current page information
                const currentPageData = {
                    path: window.location.pathname,
                    title: document.querySelector('.col-primary h1')?.textContent ||
                           document.querySelector('h1')?.textContent ||
                           document.title.split('|')[0].trim(),
                    timestamp: Date.now()
                };

                sessionStorage.setItem(STORAGE_KEY, JSON.stringify(currentPageData));
            });
        });
    };

    /**
     * Update breadcrumb on privacy page based on referrer
     */
    const updateBreadcrumb = () => {
        // Only run on privacy page
        if (window.location.pathname !== PRIVACY_PATH) return;

        // Get stored referrer data
        const storedData = sessionStorage.getItem(STORAGE_KEY);
        if (!storedData) return;

        try {
            const referrerData = JSON.parse(storedData);

            // Check if data is recent (within last 5 seconds to avoid stale data)
            const isRecent = (Date.now() - referrerData.timestamp) < 5000;
            if (!isRecent) return;

            // Find breadcrumb elements
            const breadcrumbNav = document.querySelector('nav[aria-label="breadcrumb"]');
            if (!breadcrumbNav) return;

            const breadcrumbList = breadcrumbNav.querySelector('ol');
            const breadcrumbItems = breadcrumbList.querySelectorAll('li');

            // Privacy page should show: Home / [Referrer] / Privacy
            if (breadcrumbItems.length >= 2) {
                const firstCrumb = breadcrumbItems[0];
                const firstLink = firstCrumb.querySelector('a');

                if (firstLink) {
                    // Keep the existing "Home" link, but insert referrer after it
                    const referrerCrumb = document.createElement('li');
                    const referrerLink = document.createElement('a');
                    referrerLink.href = referrerData.path;
                    referrerLink.textContent = referrerData.title;
                    referrerCrumb.appendChild(referrerLink);

                    // Insert referrer breadcrumb between Home and Privacy
                    breadcrumbList.insertBefore(referrerCrumb, breadcrumbItems[1]);
                }
            }

            // Clean up - remove from storage after using
            sessionStorage.removeItem(STORAGE_KEY);

        } catch (e) {
            // If anything fails, silently fall back to default breadcrumb
            console.error('Breadcrumb update failed:', e);
        }
    };

    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            trackNavigation();
            updateBreadcrumb();
        });
    } else {
        trackNavigation();
        updateBreadcrumb();
    }
})();
