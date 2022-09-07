<?php

declare(strict_types=1);

namespace Neo\Gist\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Neo\Gist\GistServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            GistServiceProvider::class,
        ];
    }
}
