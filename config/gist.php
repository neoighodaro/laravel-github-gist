<?php

declare(strict_types=1);

return [
    // GitHub personal token for authenticated requests
    'token' => env('GITHUB_TOKEN'),

    // Cache lifetime in minutes
    'cache_lifetime' => env('GITHUB_GIST_CACHE_LIFETIME', 0),

    // Maximum amount of files allowed in a gist
    'max_files' => env('GITHUB_GIST_MAX_FILES', 10),

    // Maximum size of a file in bytes of a single file in a gist
    'max_filesize' => env('GITHUB_GIST_MAX_FILESIZE_SINGLE_GIST', 10485760), // 10 MB

    // Throw exception on file size limit exceeded
    'max_filesize_throw' => env('GITHUB_GIST_MAX_FILESIZE_THROW', true),

    'disk' => env('GITHUB_GIST_DISK', 'public'),

    'local_disk' => 'local',
];
