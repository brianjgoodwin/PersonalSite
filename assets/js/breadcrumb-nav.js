/**
 * Dynamic Breadcrumb Navigation
 * Tracks user navigation path and updates breadcrumbs for context-sensitive pages
 */

(function() {
    'use strict';

    const STORAGE_KEY = 'nav-referrer';
    const PRIVACY_PATH = '/privacy';

    /**
     * Sanitize text content to prevent any potential XSS
     * Strips HTML tags and limits length
     */
    const sanitizeText = (text) => {
        if (!text || typeof text !== 'string') return '';

        // Iteratively remove HTML tags until none remain
        // This prevents incomplete sanitization from nested tags like <script<script>>
        let sanitized = text;
        let previous;
        do {
            previous = sanitized;
            sanitized = sanitized.replace(/<[^>]*>/g, '');
        } while (sanitized !== previous);

        // Trim whitespace and limit length to reasonable breadcrumb size
        return sanitized.trim().substring(0, 100);
    };

    /**
     * Validate that path is a safe internal path
     */
    const isValidPath = (path) => {
        if (!path || typeof path !== 'string') return false;
        // Must start with / and not contain dangerous characters
        return path.startsWith('/') &&
               !/[<>'"\\]/.test(path) &&
               path.length < 200;
    };

    /**
     * Store current page info before navigating away
     */
    const trackNavigation = () => {
        // Track clicks on all internal links
        document.querySelectorAll('a[href]').forEach(link => {
            // Only track internal links (same origin)
            if (link.origin !== window.location.origin) return;

            link.addEventListener('click', function() {
                // Don't track if it's the same page
                if (link.pathname === window.location.pathname) return;

                // Get raw data from DOM
                const rawPath = window.location.pathname;
                const rawTitle = document.querySelector('.col-primary h1')?.textContent ||
                                 document.querySelector('h1')?.textContent ||
                                 document.title.split('|')[0].trim();

                // Only store if path and title are valid
                if (isValidPath(rawPath) && rawTitle) {
                    const currentPageData = {
                        path: rawPath,
                        title: sanitizeText(rawTitle),
                        timestamp: Date.now()
                    };

                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(currentPageData));
                }
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

            // Validate and sanitize data before use
            if (!isValidPath(referrerData.path) || !referrerData.title) {
                return; // Invalid data, don't update breadcrumb
            }

            // Privacy page should show: Home / [Referrer] / Privacy
            if (breadcrumbItems.length >= 2) {
                const firstCrumb = breadcrumbItems[0];
                const firstLink = firstCrumb.querySelector('a');

                if (firstLink) {
                    // Keep the existing "Home" link, but insert referrer after it
                    const referrerCrumb = document.createElement('li');
                    const referrerLink = document.createElement('a');
                    referrerLink.href = referrerData.path;
                    referrerLink.textContent = sanitizeText(referrerData.title);
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
