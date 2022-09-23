<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Cache\Repository;
use Neo\Gist\Exception\GistClientException;

class GistClient
{
    protected int $cacheLifeTimeInMinutes = 0;

    protected string $baseUrl = 'https://api.github.com/gists';

    public function __construct(
        protected readonly array $config,
        protected readonly Repository $cache
    ) {
        $this->setCacheLifeTimeInMinutes(intval($config['cache_lifetime'] ?? 0));
    }

    public function setCacheLifeTimeInMinutes(int $cacheLifeTimeInMinutes): self
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes * 60;

        return $this;
    }

    public function getGist(string $id): array
    {
        $cacheName = $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes === 0) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes, function () use ($id) {
            $response = Http::withHeaders($this->headers())->get(
                sprintf('%s/%s', $this->baseUrl, $id)
            );

            if ($response->status() === 200 || $response->status() === 304) {
                return $response->json();
            }

            throw new GistClientException($response);
        });
    }

    public function getPublicGists(): array
    {
        $cacheName = $this->determineCacheName(['method' => 'getPublicGists']);

        if ($this->cacheLifeTimeInMinutes === 0) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes, function () {
            $response = Http::withHeaders($this->headers())->get($this->baseUrl);

            if ($response->status() === 200 || $response->status() === 304) {
                return $response->json();
            }

            throw new GistClientException($response);
        });
    }

    protected function headers(): array
    {
        $token = $this->config['token'] ?? null;
        $headers = ['Accept' => 'application/vnd.github+json'];

        if ($token) {
            $headers['Authorization'] = sprintf('Bearer %s', $token);
        }

        return $headers;
    }

    protected function determineCacheName(array $properties): string
    {
        return 'neo.laravel-github-gist.'.md5(serialize($properties));
    }
}
