<?php

declare(strict_types=1);

namespace Neo\Gist\Exception;

use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GitHubClientException extends HttpException
{
    public function __construct(public readonly Response $response)
    {
        parent::__construct(
            $response->status(),
            sprintf('Something went wrong while fetching: %s', $response->json('message')),
        );
    }
}
