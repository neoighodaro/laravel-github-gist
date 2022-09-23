<?php

declare(strict_types=1);

namespace Neo\Gist\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\DataCollectionOf;

#[MapInputName(SnakeCaseMapper::class)]
final class GistData extends Data
{
    public string $url;

    public string $forksUrl;

    public string $commitsUrl;

    public string $id;

    public string $nodeId;

    public string $gitPullUrl;

    public string $gitPushUrl;

    public string $htmlUrl;

    #[DataCollectionOf(GistFileData::class)]
    public DataCollection $files;

    public bool $public = true;

    public Carbon $createdAt;

    public Carbon $updatedAt;

    public ?string $description;

    public int $comments = 0;

    public ?string $user = null; // Object of GistUserData

    public string $commentsUrl;

    public GistOwnerData $owner;

    public bool $truncated = false;

    // public array $forks = []; // @TODO

    // public array $history = []; // @TODO
}
