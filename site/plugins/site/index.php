<?php

/**
 * Site Plugin: Cache Invalidation
 *
 * Automatically clears the page cache when content changes
 * to ensure users always see the latest version.
 */

Kirby::plugin('brianardoin/cache-invalidation', [
    'hooks' => [
        // Clear cache when any page is updated
        'page.update:after' => function ($newPage, $oldPage) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during page update: ' . $e->getMessage());
            }
        },

        'page.create:after' => function ($page) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during page creation: ' . $e->getMessage());
            }
        },

        'page.delete:after' => function ($page) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during page deletion: ' . $e->getMessage());
            }
        },

        'page.changeStatus:after' => function ($newPage, $oldPage) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during status change: ' . $e->getMessage());
            }
        },

        // Clear cache when files are uploaded/changed
        'file.create:after' => function ($file) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during file creation: ' . $e->getMessage());
            }
        },

        'file.update:after' => function ($newFile, $oldFile) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during file update: ' . $e->getMessage());
            }
        },

        'file.delete:after' => function ($file) {
            try {
                if ($cache = kirby()->cache('pages')) {
                    $cache->flush();
                }
            } catch (Exception $e) {
                error_log('Cache flush failed during file deletion: ' . $e->getMessage());
            }
        }
    ]
]);
