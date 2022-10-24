<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Contracts\Cache\Repository;
use Neo\Gist\Concerns\HasCacheableClient;
use Neo\Gist\Exception\GistClientException;
use Neo\Gist\Exception\GitHubClientException;

class GistClient
{
    use HasCacheableClient;

    protected int $cacheLifeTimeInMinutes = 0;

    protected string $baseUrl = 'https://api.github.com/gists';

    public function __construct(protected readonly array $config, Repository $cache)
    {
        $this->cache = $cache;

        $this->setCacheLifeTimeInMinutes(intval($config['cache_lifetime'] ?? 0));
    }

    /**
     * @throws \Neo\Gist\Exception\GistClientException
     */
    public function getGist(string $id): array
    {
        try {
            return $this->getCachedRequest(
                $this->baseUrl.'/'.$id,
                $this->determineCacheName([$id])
            );
        } catch (GitHubClientException $e) {
            throw new GistClientException($e->response);
        }
    }

    /**
     * @throws \Neo\Gist\Exception\GistClientException
     */
    public function getPublicGists(): array
    {
        try {
            return $this->getCachedRequest(
                $this->baseUrl,
                $this->determineCacheName(['method' => 'getPublicGists'])
            );
        } catch (GitHubClientException $e) {
            throw new GistClientException($e->response);
        }
    }
}
