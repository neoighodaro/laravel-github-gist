<?php

declare(strict_types=1);

namespace Neo\Gist\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
final class GistFileData extends Data
{
    public string $filename;

    public string $type;

    public ?string $language;

    public string $rawUrl;

    public int $size;
}
