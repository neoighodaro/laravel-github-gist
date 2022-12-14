<?php

declare(strict_types=1);

namespace Neo\Gist;

use Neo\Gist\Data\GitHubData;
use Neo\Gist\Types\GitHubRepoUrl;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

final class GitHub
{
    private FilesystemAdapter $localStorage;

    public function __construct(
        protected readonly GitHubClient $client,
        protected readonly FilesystemAdapter $storage
    ) {
        $this->localStorage = Storage::disk(Config::get('gist.local_disk'));
    }

    public function withCache(int $minutes): self
    {
        $this->client->setCacheLifeTimeInMinutes($minutes);

        return $this;
    }

    /**
     * @throws \Neo\Gist\Exception\GithubClientException
     */
    public function get(GitHubRepoUrl $url): GitHubData
    {
        return GitHubData::from($this->client->getRepo($url));
    }

    public function getDownloadUrl(string $url): string
    {
        return $this->get(GitHubRepoUrl::fromUrl($url))->getZipUrl();
    }
}
