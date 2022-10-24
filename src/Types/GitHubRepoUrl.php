<?php

declare(strict_types=1);

namespace Neo\Gist\Types;

use InvalidArgumentException;

final class GitHubRepoUrl
{
    protected function __construct(
        public readonly string $url,
        public readonly string $path,
    ) {
    }

    public static function fromUrl(string $url): self
    {
        return GitHubRepoUrl::usingRegex('/^https?:\/\/(www\.)?github.com\/(?<owner>[\w.-]+)\/(?<repo>[\w.-]+)/', $url);
    }

    public static function fromPath(string $path): self
    {
        return GitHubRepoUrl::usingRegex('/^(?<owner>[\w.-]+)\/(?<repo>[\w.-]+)/', $path);
    }

    protected static function usingRegex(string $pattern, string $subject): self
    {
        preg_match($pattern, $subject, $matches);

        $repo = $matches['repo'] ?? null;
        $owner = $matches['owner'] ?? null;

        if ($repo === null || $owner === null) {
            throw new InvalidArgumentException(__('Could figure out repository from :subject', [
                'subject' => $subject,
            ]));
        }

        $path = $owner.'/'.$repo;

        return new GitHubRepoUrl(
            path: $path,
            url: filter_var($subject, FILTER_VALIDATE_URL) ? $subject : 'https://github.com/'.$path,
        );
    }
}
