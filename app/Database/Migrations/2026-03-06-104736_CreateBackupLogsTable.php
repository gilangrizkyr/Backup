<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackupLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'database_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'backup_date' => [
                'type' => 'DATETIME',
            ],
            'file_size' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('backup_logs');
    }

    public function down()
    {
        $this->forge->dropTable('backup_logs');
    }
}
