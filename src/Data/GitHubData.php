<?php

declare(strict_types=1);

namespace Neo\Gist\Data;

use Illuminate\Support\Carbon;
use RuntimeException;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
final class GitHubData extends Data
{
    public bool $private = false;

    public bool $archived = false;

    public string $defaultBranch = 'main';

    public string $htmlUrl;

    public Carbon $updatedAt;

    public Carbon $createdAt;

    public function getZipUrl(): string
    {
        if ($this->private) {
            throw new RuntimeException('Cannot download private repository');
        }

        return $this->htmlUrl.'/zipball/'.$this->defaultBranch;
    }
}
