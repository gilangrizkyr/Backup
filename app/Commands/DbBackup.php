<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DbBackup extends BaseCommand
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
    protected $name = 'db:backup';
    protected $description = 'Perform automatic database backup of all databases.';
    protected $usage = 'db:backup';

    public function run(array $params)
    {
        CLI::write('Starting Daily Backup...', 'yellow');

        $backupController = new \App\Controllers\BackupController();
        $backupController->backup();

        CLI::write('Daily Backup Completed successfully.', 'green');
    }
}
