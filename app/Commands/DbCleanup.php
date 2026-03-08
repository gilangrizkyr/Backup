<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DbCleanup extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'db:cleanup';
    protected $description = 'Cleanup backups older than 30 days.';
    protected $usage = 'db:cleanup';

    public function run(array $params)
    {
        CLI::write('Starting Cleanup...', 'yellow');

        $backupPath = WRITEPATH . 'backups/';
        if (!is_dir($backupPath)) {
            CLI::write('Backup directory not found. Skipping.', 'red');
            return;
        }

        $folders = scandir($backupPath);
        $threshold = strtotime('-30 days');

        foreach ($folders as $folder) {
            if ($folder === '.' || $folder === '..')
                continue;

            $folderPath = $backupPath . $folder;
            if (is_dir($folderPath)) {
                $folderTime = strtotime($folder); // Folders are named YYYY-MM-DD
                if ($folderTime && $folderTime < $threshold) {
                    CLI::write("Deleting old backup folder: $folder", 'white');
                    $this->deleteDirectory($folderPath);
                }
            }
        }

        CLI::write('Cleanup Completed successfully.', 'green');
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                return false;
        }
        return rmdir($dir);
    }
}
