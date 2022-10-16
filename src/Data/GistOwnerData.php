<?php

declare(strict_types=1);

namespace Neo\Gist\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
final class GistOwnerData extends Data
{
    public ?string $name = null;

    public ?string $email = null;

    public string $login;

    public int $id;

    public string $nodeId;

    public string $avatarUrl;

    public ?string $gravatarId = null;

    public string $url;

    public string $htmlUrl;

    public string $followersUrl;

    public string $followingUrl;

    public string $gistsUrl;

    public string $starredUrl;

    public string $subscriptionsUrl;

    public string $organizationsUrl;

    public string $reposUrl;

    public string $eventsUrl;

    public string $receivedEventsUrl;

    public string $type;

    public bool $siteAdmin;
}
