<?php

namespace Tests\Support;

use Illuminate\Support\Facades\Log;

class MemberTestLogger
{
    private static string $logPath;

    public static function start(string $test): void
    {
        self::$logPath = storage_path('logs/member-testing.log');

        if (!is_dir(dirname(self::$logPath))) {
            mkdir(dirname(self::$logPath), 0755, true);
        }

        file_put_contents(self::$logPath, '');

        self::write('STARTED', $test, [
            'message' => 'Test execution started.',
        ]);
    }

    public static function passed(string $test, array $details = []): void
    {
        self::write('PASSED', $test, $details);
    }

    public static function failed(string $test, array $details = []): void
    {
        self::write('FAILED', $test, $details);
    }

    public static function info(string $test, array $details = []): void
    {
        self::write('INFO', $test, $details);
    }

    private static function write(
        string $status,
        string $test,
        array $details = []
    ): void {
        $path = self::$logPath ?? storage_path('logs/member-testing.log');

        Log::build([
            'driver' => 'single',
            'path' => $path,
            'level' => 'debug',
        ])->debug('MEMBER TEST', [
            'status' => $status,
            'test' => $test,
            'details' => $details,
            'time' => now()->toDateTimeString(),
        ]);
    }
}
