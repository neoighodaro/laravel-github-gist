<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Contracts\Cache\Repository;

final class GistClientFactory
{
    public static function createForConfig(array $config): GistClient
    {
        return new GistClient($config, app(Repository::class));
    }
}
