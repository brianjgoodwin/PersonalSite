<?php

return [
    // Debug mode - shows detailed errors
    'debug' => false,

    // Routes
    'routes' => [
        [
            'pattern' => 'sitemap.xml',
            'method' => 'GET',
            'action'  => function() {
                $sitemap = snippet('sitemap', [], true);
                return new Kirby\Cms\Response($sitemap, 'application/xml');
            }
        ],
        [
            'pattern' => 'feed.xml',
            'method' => 'GET',
            'action'  => function() {
                $feed = snippet('feed', [], true);
                return new Kirby\Cms\Response($feed, 'application/xml');
            }
        ],
        [
            'pattern' => 'feed',
            'method' => 'GET',
            'action'  => function() {
                return go('feed.xml', 301);
            }
        ],
        [
            'pattern' => 'documentation(.*)',
            'action'  => function() {
                // Block all frontend access to documentation pages
                // These are Panel-only pages for internal documentation
                return kirby()->site()->errorPage();
            }
        ]
    ],

    // Security
    // Disable Vue template compiler for better security and performance
    'panel' => [
        'vueDevtools' => false,
        'vue.compiler' => false
    ],

    // Markdown Safe Mode - Disabled to allow KirbyTags (image, video, etc) to render
    // KirbyTags generate HTML that needs to be rendered, not escaped
    'markdown' => [
        'safe' => false
    ],

    // Page Caching - Improves performance by caching rendered pages
    // DISABLED during inline-styles refactoring for immediate CSS updates
    'cache' => [
        'pages' => [
            'active' => false,
            'ignore' => function ($page) {
                try {
                    // Never cache panel or authenticated pages
                    if (method_exists($page, 'isPanel') && $page->isPanel()) {
                        return true;
                    }

                    if (kirby()->user()) {
                        return true;
                    }

                    // CRITICAL: Never cache dynamic feeds/sitemaps
                    if (method_exists($page, 'intendedTemplate')) {
                        $intendedTemplate = $page->intendedTemplate();
                        if ($intendedTemplate && method_exists($intendedTemplate, 'name')) {
                            $template = $intendedTemplate->name();
                            if (in_array($template, ['feed', 'sitemap.xml'])) {
                                return true;
                            }
                        }
                    }

                    return false;
                } catch (Exception $e) {
                    // If we can't determine, don't cache to be safe
                    return true;
                }
            }
        ]
    ],

    // HTTP Headers - Smart caching and security
    'headers' => [
        'Cache-Control' => function () {
            // Different cache headers based on authentication
            if (kirby()->user()) {
                return 'private, no-cache, no-store, must-revalidate';
            }

            // Check current route/path for dynamic feeds (routes don't have pages)
            $path = kirby()->request()->path()->toString();

            // Feed: Short cache (5 minutes)
            if (in_array($path, ['feed', 'feed.xml'])) {
                return 'public, max-age=300, s-maxage=600';
            }

            // Sitemap: Medium cache (1 hour)
            if ($path === 'sitemap.xml') {
                return 'public, max-age=3600, s-maxage=7200';
            }

            // For actual pages, check template
            $page = kirby()->site()->page();
            if ($page) {
                $template = $page->intendedTemplate() ? $page->intendedTemplate()->name() : null;

                // Blog posts: Long cache (24 hours)
                if ($template === 'post') {
                    return 'public, max-age=86400, s-maxage=172800, immutable';
                }
            }

            // Static pages: Medium-long cache (4 hours)
            return 'public, max-age=14400, s-maxage=28800';
        },

        'Vary' => 'Cookie, Accept-Encoding',

        // Security Headers
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',

        // Content Security Policy - Allows YouTube/Vimeo embeds
        'Content-Security-Policy' => implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https:",
            "font-src 'self' data:",
            "frame-src https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ])
    ],

    // Git Content Plugin Configuration
    // https://github.com/thathoff/kirby-git-content
    // Only enabled in production (disabled on localhost for testing)

    // Validate server environment - whitelist approach for security
    // This prevents HTTP Host header injection attacks
    'isProduction' => (function() {
        $allowedLocalHosts = ['localhost', '127.0.0.1', '::1'];
        $serverName = $_SERVER['SERVER_NAME'] ?? '';

        // Only disable git features if we're definitely on localhost
        // If SERVER_NAME doesn't match localhost patterns, assume production
        return !in_array($serverName, $allowedLocalHosts, true);
    })(),

    // Enable automatic commits when content changes via Panel
    'thathoff.git-content.commit' => (function() {
        $allowedLocalHosts = ['localhost', '127.0.0.1', '::1'];
        $serverName = $_SERVER['SERVER_NAME'] ?? '';
        return !in_array($serverName, $allowedLocalHosts, true);
    })(),

    // Automatically pull changes from remote before making changes
    'thathoff.git-content.pull' => false,

    // Automatically push commits to remote repository
    'thathoff.git-content.push' => (function() {
        $allowedLocalHosts = ['localhost', '127.0.0.1', '::1'];
        $serverName = $_SERVER['SERVER_NAME'] ?? '';
        return !in_array($serverName, $allowedLocalHosts, true);
    })(),

    // Path to git repository (defaults to content directory)
    // 'thathoff.git-content.path' => kirby()->root('content'),
];
