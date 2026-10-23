<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ContractDocumentStorage
{
    public const DISK = 'local';

    public const LEGACY_DISK = 'public';

    public static function disk(): Filesystem
    {
        return Storage::disk(self::DISK);
    }

    public static function store(string $directory, mixed $file): string
    {
        return $file->store($directory, self::DISK);
    }

    public static function exists(string $path): bool
    {
        return self::disk()->exists($path)
            || Storage::disk(self::LEGACY_DISK)->exists($path);
    }

    public static function response(string $path, string $name, array $headers = [], string $disposition = 'attachment')
    {
        $disk = self::disk()->exists($path) ? self::disk() : Storage::disk(self::LEGACY_DISK);
        $headers['Content-Type'] = $headers['Content-Type'] ?? self::mimeFor($path);

        return $disk->response($path, $name, $headers, $disposition);
    }

    private static function mimeFor(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream',
        };
    }
}
