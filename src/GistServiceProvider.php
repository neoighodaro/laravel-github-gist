<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GistServiceProvider extends PackageServiceProvider
{
    /**
     * @see https://github.com/spatie/laravel-package-tools
     */
    public function configurePackage(Package $package): void
    {
        $package->name('gist')->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(GistClient::class, fn () => GistClientFactory::createForConfig(Config::get('gist')));

        $this->app->bind(Gist::class, fn () => new Gist(
            app(GistClient::class),
            app(Factory::class)->disk(Config::get('gist.disk')),
        ));

        $this->app->alias(Gist::class, 'laravel-github-gist');
    }
}
