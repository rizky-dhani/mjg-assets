<?php

namespace App\Services;

use App\Models\DatabaseBackup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupService
{
    public function createBackup(DatabaseBackup $backup): bool
    {
        $backup->update([
            'status' => 'pending',
            'started_at' => now(),
        ]);

        try {
            $config = config('database.connections.'.config('database.default'));

            $filename = $backup->filename;
            $disk = Storage::disk($backup->disk);
            $directory = 'database_backups';

            if (! $disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }

            $path = $directory.'/'.$filename;
            $tempPath = storage_path('app/'.$path);

            // Ensure temp directory exists
            $tempDir = dirname($tempPath);
            if (! is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Build mysqldump command
            $command = $this->buildMysqldumpCommand($config, $tempPath);

            // Execute backup — stderr goes to a separate file to keep the dump clean
            $stderrPath = $tempPath.'.stderr';
            $command .= ' 2>'.escapeshellarg($stderrPath);

            $output = [];
            $exitCode = 0;
            exec($command, $output, $exitCode);

            // Capture any stderr output
            $stderrContent = '';
            if (file_exists($stderrPath)) {
                $stderrContent = file_get_contents($stderrPath);
                File::delete($stderrPath);
            }

            if (! file_exists($tempPath) || filesize($tempPath) === 0) {
                $errorMsg = $stderrContent ?: 'Unknown error';
                if ($exitCode !== 0) {
                    throw new \RuntimeException('mysqldump failed (exit code '.$exitCode.'): '.$errorMsg);
                }
                throw new \RuntimeException('Backup file was not created or is empty: '.$errorMsg);
            }


            $size = filesize($tempPath);

            // Store to disk
            $disk->put($path, file_get_contents($tempPath));

            // Clean up temp file
            File::delete($tempPath);

            $backup->update([
                'status' => 'completed',
                'path' => $path,
                'size' => $size,
                'completed_at' => now(),
            ]);

            return true;

        } catch (\Exception $e) {
            $backup->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return false;
        }
    }

    protected function buildMysqldumpCommand(array $config, string $tempPath): string
    {
        $host = escapeshellarg($config['host']);
        $port = escapeshellarg($config['port'] ?? '3306');
        $database = escapeshellarg($config['database']);
        $username = escapeshellarg($config['username']);
        $password = $config['password'];

        $mysqldumpPath = $this->findMysqldump();

        $command = escapeshellarg($mysqldumpPath);
        $command .= " --host={$host}";
        $command .= " --port={$port}";
        $command .= " --user={$username}";

        if (! empty($password)) {
            $command .= ' --password='.escapeshellarg($password);
        }

        $command .= ' --single-transaction';
        $command .= ' --routines';
        $command .= ' --triggers';
        $command .= ' --events';
        $command .= ' --quick';
        $command .= ' --lock-tables=false';
        $command .= " {$database}";
        $command .= ' > '.escapeshellarg($tempPath);

        return $command;
    }

    protected function findMysqldump(): string
    {
        // 1. Explicit .env override
        $envPath = env('DB_BACKUP_MYSQLDUMP_PATH');
        if ($envPath && file_exists($envPath)) {
            return $envPath;
        }

        // 2. Check system PATH
        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        exec($which.' mysqldump 2>&1', $output, $exitCode);
        if ($exitCode === 0 && ! empty($output[0]) && file_exists($output[0])) {
            return trim($output[0]);
        }

        // 3. Windows: scan common locations (Laragon, XAMPP, MySQL default install)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidates = glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe')
                + glob('C:/laragon/bin/mariadb/*/bin/mysqldump.exe')
                + glob('C:/xampp/mysql/bin/mysqldump.exe')
                + glob('C:/Program Files/MySQL/*/bin/mysqldump.exe')
                + glob('C:/Program Files/MariaDB/*/bin/mysqldump.exe');

            foreach ($candidates as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        throw new \RuntimeException(
            'mysqldump not found. Set DB_BACKUP_MYSQLDUMP_PATH in .env to the full path of mysqldump.'
        );
    }

    public function deleteBackup(DatabaseBackup $backup): bool
    {
        if ($backup->status === 'completed' && Storage::disk($backup->disk)->exists($backup->path)) {
            Storage::disk($backup->disk)->delete($backup->path);
        }

        return $backup->delete();
    }
}
