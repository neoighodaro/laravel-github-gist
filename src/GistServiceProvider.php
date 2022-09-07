<?php

declare(strict_types=1);

namespace Neo\Gist;

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
}
