<?php

return [
    /**
     * Directories that the package will scan for the #[Autowire] attribute.
     */
    'scan_directories' => [
        base_path('src/Infrastructure'),
    ],

    /**
     * The absolute path where the generated binding map will be cached.
     */
    'cache_path' => base_path('bootstrap/cache/autowire_bindings.php'),

    /**
     * Determine if the binder should rely on the cached map file.
     *
     * - Leave null (default) to automatically enable caching ONLY in production environments.
     * - Set to true/false (manually or via env) to override and force/disable caching.
     */
    'use_cache' => env('AUTOWIRE_CACHE', null),
];
