<?php

// File: app/Database/Migrations/CreateActivityLog.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLog extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'message' => [
                'type' => 'TEXT'
            ],
            'context' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_logs');
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs');
    }
}
