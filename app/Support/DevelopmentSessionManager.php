<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DevelopmentSessionManager
{
    private const TOKEN_PATH = 'framework/dev-server.token';

    public static function reset(): string
    {
        self::clearSessionStorage();

        return self::rotateServerToken();
    }

    public static function clearSessionStorage(): void
    {
        $driver = config('session.driver');

        match ($driver) {
            'file' => self::clearFileSessions(),
            'database' => DB::table(config('session.table', 'sessions'))->delete(),
            default => null,
        };
    }

    public static function rotateServerToken(): string
    {
        $token = (string) Str::uuid();
        $path = storage_path(self::TOKEN_PATH);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $token);

        return $token;
    }

    public static function currentServerToken(): ?string
    {
        $path = storage_path(self::TOKEN_PATH);

        if (! File::exists($path)) {
            return null;
        }

        $token = trim(File::get($path));

        return $token !== '' ? $token : null;
    }

    private static function clearFileSessions(): void
    {
        $path = storage_path('framework/sessions');

        if (! is_dir($path)) {
            return;
        }

        foreach (File::files($path) as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file->getPathname());
            }
        }
    }
}
