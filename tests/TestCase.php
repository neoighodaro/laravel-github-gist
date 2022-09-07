<?php

declare(strict_types=1);

namespace Neo\Gist\Tests;

use Neo\Gist\GistServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            GistServiceProvider::class,
        ];
    }
}
