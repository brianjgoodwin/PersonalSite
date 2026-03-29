<?php

/**
 * Site Blueprint - Custom Panel Dashboard
 * 
 * Two-column layout with:
 * - Left: All site pages
 * - Right: Recent posts, recent projects, placeholder
 */

return [
    'title' => 'Site',
    'columns' => [
        // Left Column - Pages
        'left' => [
            'width' => '1/2',
            'sections' => [
                'pages' => [
                    'type' => 'pages',
                    'headline' => 'All Pages',
                    'layout' => 'list',
                    'icon' => '{{ page.icon }}',
                    'empty' => 'No pages yet'
                ]
            ]
        ],

        // Right Column - Recent Content
        'right' => [
            'width' => '1/2',
            'sections' => [
                'recentPosts' => [
                    'type' => 'pages',
                    'headline' => 'Recent Posts',
                    'parent' => 'site.find("posts")',
                    'status' => 'all',
                    'limit' => 5,
                    'layout' => 'list',
                    'info' => '{{ page.date.toDate("M d, Y") }}',
                    'text' => '{{ page.title }}',
                    'create' => 'article',
                    'empty' => 'No posts yet. Create your first post!'
                ],
                'recentProjects' => [
                    'type' => 'pages',
                    'headline' => 'Recent Projects',
                    'parent' => 'site.find("projects")',
                    'limit' => 3,
                    'layout' => 'list',
                    'info' => '{{ page.modified("M d, Y") }}',
                    'create' => 'project',
                    'empty' => 'No projects yet. Create your first project!'
                ],
                'placeholder' => [
                    'type' => 'info',
                    'text' => 'Future expansion area'
                ]
            ]
        ]
    ]
];
