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
     * Leave null (default) to automatically enable caching in production environments.
     * To override this behavior, set the AUTOWIRE_CACHE variable in your .env file (true/false).
     */
    'use_cache' => env('AUTOWIRE_CACHE', null),
];
