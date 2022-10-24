<?php

declare(strict_types=1);

namespace Neo\Gist\Concerns;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\PendingRequest;
use Neo\Gist\Exception\GithubClientException;
use Symfony\Component\HttpFoundation\Response;

trait HasCacheableClient
{
    protected int $cacheLifeTimeInMinutes = 0;

    protected readonly Repository $cache;

    protected function http(): PendingRequest
    {
        return Http::withHeaders($this->clientHeaders());
    }

    protected function getCachedRequest(string $url, ?string $cacheName = null): array
    {
        $cacheName ??= $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes === 0 && $this->cache->has($cacheName)) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes, function () use ($url) {
            $response = $this->http()->get($url);

            if ($response->status() === Response::HTTP_OK || $response->status() === Response::HTTP_NOT_MODIFIED) {
                return $response->json();
            }

            throw new GithubClientException($response);
        });
    }

    public function setCacheLifeTimeInMinutes(int $cacheLifeTimeInMinutes): self
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes * 60;

        return $this;
    }

    protected function determineCacheName(array $properties): string
    {
        return Config::get('gist.cache_name_prefix', 'neo.laravel-github-gist.').md5(serialize($properties));
    }

    protected function clientHeaders(): array
    {
        $token = $this->config['token'] ?? null;
        $headers = ['Accept' => 'application/vnd.github+json'];

        if ($token) {
            $headers['Authorization'] = sprintf('Bearer %s', $token);
        }

        return $headers;
    }
}
