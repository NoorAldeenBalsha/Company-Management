<?php
declare(strict_types=1);

class AppLogger {
    

    private static string $logFile = '';

    // Initialize the logger with the target file path.
    public static function init(string $filePath): void {
        self::$logFile = $filePath;
        $dir = dirname(self::$logFile);
        
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }


    // Write a new log entry to the system audit trail.
    public static function log(string $action, string $details, string $level = 'INFO'): void {
        if (empty(self::$logFile)) return;
        
        // Fetch current time and client IP address
        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Format: [Date] [Level] [IP] [Action] - Details
        $logEntry = sprintf("[%s] [%s] [IP: %s] [%s] - %s", $date, $level, $ip, $action, $details) . PHP_EOL;
        
        // Append to the file with an exclusive lock to prevent race conditions
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    // Read all log entries and return them as an array.
    public static function readLogs(): array {
        if (!file_exists(self::$logFile)) return [];
        
        // Read file into an array, ignoring new lines and skipping empty ones
        $lines = file(self::$logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        // Reverse array for LIFO (Last In, First Out) display in the Terminal UI
        return array_reverse($lines);
    }
}