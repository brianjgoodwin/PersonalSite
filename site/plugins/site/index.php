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
            kirby()->cache('pages')->flush();
        },

        'page.create:after' => function ($page) {
            kirby()->cache('pages')->flush();
        },

        'page.delete:after' => function ($page) {
            kirby()->cache('pages')->flush();
        },

        'page.changeStatus:after' => function ($newPage, $oldPage) {
            kirby()->cache('pages')->flush();
        },

        // Clear cache when files are uploaded/changed
        'file.create:after' => function ($file) {
            kirby()->cache('pages')->flush();
        },

        'file.update:after' => function ($newFile, $oldFile) {
            kirby()->cache('pages')->flush();
        },

        'file.delete:after' => function ($file) {
            kirby()->cache('pages')->flush();
        }
    ]
]);
