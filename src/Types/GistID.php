<?php

namespace Neo\Gist\Types;

use Illuminate\Support\Arr;
use InvalidArgumentException;

final class GistID
{
    public function __construct(protected readonly string $value)
    {
    }

    public static function fromUrl(string $url): self
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL provided.');
        }

        $id = Arr::last(explode('/', parse_url($url, PHP_URL_PATH)));

        return new self($id);
    }

    public static function fromString(string $input): self
    {
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            return self::fromUrl($input);
        }

        return new self($input);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
