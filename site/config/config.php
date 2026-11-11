<?php

/**
 * Is this the real production server?
 *
 * ALLOWLIST, not a denylist. The previous version listed three "safe"
 * hostnames (localhost, 127.0.0.1, ::1) and treated everything else as
 * production — so ANY other hostname armed git-content auto-commit and
 * auto-push to the real remote. That included LAN IPs, Tailscale and ngrok
 * addresses, and anything else you might bind a dev server to. Running
 * `php -S 192.168.x.x:8000` for device testing was enough to arm it.
 *
 * Now production must be named explicitly, so anything unrecognised gets
 * the safe (disabled) behaviour by construction.
 *
 * These must match the server's SERVER_NAME exactly. If Panel edits stop
 * being committed after a deploy, check here first: a mismatch fails safe
 * (disabled), which is the right direction but is silent.
 *
 * SERVER_NAME comes from the server config rather than the Host header, so
 * it is not attacker-controlled the way HTTP_HOST would be.
 */
$productionHosts = ['brianjgoodwin.net', 'www.brianjgoodwin.net'];
$isProduction = in_array($_SERVER['SERVER_NAME'] ?? '', $productionHosts, true);

return [
    // Debug mode - shows detailed errors
    'debug' => false,

    // Byline shown on articles. Single-author site, so this is a config
    // value rather than a per-page field. Promote to a site.txt field if
    // it ever needs to be Panel-editable or vary by post.
    'site.author' => 'Brian Goodwin',


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
        ],

        // Draft preview, non-production only.
        //
        // Test posts live in content/2_posts/_drafts/ so they stay out of
        // the sitemap, the RSS feed and the /posts index. But Kirby gives a
        // draft no preview URL without an authenticated Panel session, and
        // the Panel is deliberately unusable on a dev host (see
        // $isProduction at the top of this file) — which would leave layout
        // work with no way to view its own test content.
        //
        // /draft/posts/synthetic-1500 renders that draft. The route is not
        // registered at all in production, and it still refuses anything
        // that is not actually a draft.
        [
            'pattern' => 'draft/(:all)',
            'action'  => function (string $path) use ($isProduction) {
                if ($isProduction === true) {
                    return kirby()->site()->errorPage();
                }

                $page = kirby()->site()->index(true)->find($path);

                return $page && $page->isDraft()
                    ? $page->render()
                    : kirby()->site()->errorPage();
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

    // Sitemap Configuration
    // Pages to exclude from sitemap.xml
    'sitemap.ignore' => ['error', 'documentation'],

    // Page Caching - Improves performance by caching rendered pages
    // Cache invalidation handled by site plugin (flushes on content changes)
    'cache' => [
        'pages' => [
            'active' => true,
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
            "script-src 'self' 'unsafe-inline' https://scripts.simpleanalyticscdn.com",
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
    //
    // See $isProduction at the top of this file.
    'isProduction' => $isProduction,

    // Enable automatic commits when content changes via Panel
    'thathoff.git-content.commit' => $isProduction,

    // Automatically pull changes from remote before making changes
    'thathoff.git-content.pull' => false,

    // Automatically push commits to remote repository
    'thathoff.git-content.push' => $isProduction,

    // Path to git repository (defaults to content directory)
    // 'thathoff.git-content.path' => kirby()->root('content'),
];
