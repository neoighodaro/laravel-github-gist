<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Contracts\Cache\Repository;
use Neo\Gist\Concerns\HasCacheableClient;
use Neo\Gist\Types\GitHubRepoUrl;

final class GitHubClient
{
    use HasCacheableClient;

    protected string $baseUrl = 'https://api.github.com/repos';

    public function __construct(protected readonly array $config, Repository $cache)
    {
        $this->cache = $cache;
        $this->setCacheLifeTimeInMinutes(intval($config['cache_lifetime'] ?? 0));
    }

    public function getRepo(GitHubRepoUrl $repo): array
    {
        return $this->getCachedRequest($this->baseUrl.'/'.$repo->path, $this->determineCacheName([$repo]));
    }
}
