<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecretsTable extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addField([
            'id'               => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ],
            'hash'             => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => false
            ],
            'bodytext'         => [
                'type'           => 'text',
                'null'           => false
            ],
            'created_at'       => [
                'type'           => 'DATETIME',
                'null'           => false
            ],
            'expires_at'       => [
                'type'           => 'DATETIME',
                'null'           => true
            ],
            'remaining_views'       => [
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
                'default'        => 0
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('secrets', true);
    }

    public function down()
    {
        $this->forge->dropTable('secrets', true);
    }
}
