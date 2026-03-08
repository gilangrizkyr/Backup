<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\BackupModel;

class BackupController extends BaseController
{
    protected $backupModel;

    public function __construct()
    {
        $this->backupModel = new BackupModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $date = $this->request->getGet('date');

        $query = $this->backupModel;

        if ($search) {
            $query = $query->like('database_name', $search);
        }

        if ($date) {
            $query = $query->like('backup_date', $date);
        }

        $data = [
            'title' => 'Backup Manager',
            'backups' => $query->orderBy('created_at', 'DESC')->findAll(),
            'search' => $search,
            'date' => $date,
        ];

        return view('backup/index', $data);
    }

    public function backup()
    {
        $db = \Config\Database::connect();
        $databases = $db->query("SHOW DATABASES")->getResultArray();

        $backupPath = WRITEPATH . 'backups/' . date('Y-m-d') . '/';
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true);
        }

        $host = env('database.default.hostname');
        $user = env('database.default.username');
        $pass = env('database.default.password');

        $excludedDbs = ['information_schema', 'mysql', 'performance_schema', 'sys', 'db_backup_manager'];

        foreach ($databases as $database) {
            $dbName = $database['Database'];

            if (in_array($dbName, $excludedDbs)) {
                continue;
            }

            $fileName = $dbName . '.sql.gz';
            $fullPath = $backupPath . $fileName;

            // Securely run mysqldump and compress
            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s | gzip > %s',
                escapeshellarg($host),
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );

            shell_exec($command);

            if (file_exists($fullPath)) {
                $this->backupModel->save([
                    'database_name' => $dbName,
                    'file_name' => $fileName,
                    'backup_date' => date('Y-m-d H:i:s'),
                    'file_size' => $this->formatBytes(filesize($fullPath)),
                ]);
            }
        }

        return redirect()->to('/backup')->with('success', 'Backup successfully completed.');
    }

    public function download($id)
    {
        $backup = $this->backupModel->find($id);

        if (!$backup) {
            return redirect()->to('/backup')->with('error', 'File not found.');
        }

        $dateFolder = date('Y-m-d', strtotime($backup['backup_date']));
        $filePath = WRITEPATH . 'backups/' . $dateFolder . '/' . $backup['file_name'];

        if (file_exists($filePath)) {
            // Log download if needed
            return $this->response->download($filePath, null);
        }

        return redirect()->to('/backup')->with('error', 'File not found on server.');
    }

    public function manualBackup()
    {
        $password = $this->request->getPost('password');

        // Very basic password check - in real app, check against user password hash
        // For this demo, let's assume we expect the admin to confirm their session
        if ($password === 'admin123') { // Replace with actual validation logic
            return $this->backup();
        }

        return redirect()->to('/backup')->with('error', 'Invalid password confirmation.');
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
