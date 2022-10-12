<?php

declare(strict_types=1);

namespace Neo\Gist;

use ZipArchive;
use Neo\Gist\Data\GistData;
use Neo\Gist\Data\GistFileData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Storage;
use Neo\Gist\Exception\GistSaveException;
use Illuminate\Filesystem\FilesystemAdapter;

class Gist
{
    private FilesystemAdapter $localStorage;

    private array $disallowedFileNames = [
        'boost.config.json',
    ];

    public function __construct(
        protected readonly GistClient $client,
        protected readonly FilesystemAdapter $storage
    ) {
        $this->localStorage = Storage::disk(Config::get('gist.local_disk'));
    }

    public function withCache(int $minutes): self
    {
        $this->client->setCacheLifeTimeInMinutes($minutes);

        return $this;
    }

    /**
     * @throws \Neo\Gist\Exception\GistClientException
     */
    public function get(string $gistId): GistData
    {
        return GistData::from($this->client->getGist($gistId));
    }

    /**
     * @throws \Neo\Gist\Exception\GistClientException
     */
    public function getPublic(): DataCollection
    {
        return GistData::collection($this->client->getPublicGists());
    }

    /**
     * @throws \Neo\Gist\Exception\GistSaveException
     */
    public function save(GistData $gist): string
    {
        $this->validate($gist);

        $fileName = $this->getStorageFileName($gist);
        $zipPath = $this->localStorage->path($fileName);

        $zip = new ZipArchive();
        $openedZip = $zip->open($zipPath, ZipArchive::CREATE);

        if ($openedZip !== true) {
            throw new GistSaveException(__('Could not create zip file in :path', ['path' => $zipPath]));
        }

        // Save each file from the Gist to Zip
        $this->getRawGistUrls($gist)->each(
            static fn (string $url, string $fileName) => $zip->addFromString($fileName, Http::get($url)->body())
        );

        $zip->close();

        $this->storage->writeStream($fileName, fopen($zipPath, 'r'));
        $this->localStorage->delete($fileName);

        return $this->storage->path($fileName);
    }

    public function fileExists(string $path): bool
    {
        return $this->storage->exists($path);
    }

    public function getStorageFileName(GistData $gist): string
    {
        return sprintf('%s.zip', $gist->id);
    }

    /**
     * @param  \Neo\Gist\Data\GistData  $gist
     * @return Collection<string, string>
     */
    protected function getRawGistUrls(GistData $gist): Collection
    {
        $max = intval(Config::get('gist.max_filesize'));

        return $gist->files
            ->toCollection()
            ->when($max > 0, fn (Collection $files) => $files->filter(fn (GistFileData $file) => $file->size <= $max))
            ->filter(fn (GistFileData $file) => ! in_array($file->filename, $this->disallowedFileNames))
            ->pluck('rawUrl', 'filename');
    }

    /**
     * @throws \Neo\Gist\Exception\GistSaveException
     */
    protected function validate(GistData $gist): void
    {
        $filesCount = $gist->files->count();
        $maxFiles = intval(Config::get('gist.max_files'));

        if ($filesCount === 0) {
            throw new GistSaveException(__('Gist has no files'));
        }

        if ($filesCount > $maxFiles) {
            throw new GistSaveException(__('Gist has too many files. Maximum: :max', [
                'max' => Config::get('gist.max_files'),
            ]));
        }

        $max = intval(Config::get('gist.max_filesize'));
        $throwExceptionIfAnyFileExceedsMax = boolval(Config::get('gist.max_filesize_throw'));

        if ($max > 0 && $throwExceptionIfAnyFileExceedsMax) {
            $gist->files->each(static function (GistFileData $file) use ($max) {
                throw_if($file->size > $max, GistSaveException::class, __('File :file is too big. Maximum: :max', [
                    'max' => $max,
                    'file' => $file->filename,
                ]));
            });
        }
    }
}
