<?php

declare(strict_types=1);

namespace Neo\Gist;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Filesystem\Factory;
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

        $this->app->bind(
            GitHubClient::class,
            static fn () => new GitHubClient(Config::get('gist'), app(Repository::class))
        );

        $this->app->bind(GitHub::class, fn () => new GitHub(
            app(GitHubClient::class),
            app(Factory::class)->disk(Config::get('gist.disk')),
        ));

        $this->app->alias(GitHub::class, 'laravel-github');
    }
}
