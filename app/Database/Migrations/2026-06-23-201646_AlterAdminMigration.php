<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterAdminMigration extends Migration
{
    public function up()
    {
        $fields = [
            'foto_profil' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'nama'
            ],
            'remember_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password'
            ]
        ];
        $this->forge->addColumn('admin', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('admin', 'foto_profil');
        $this->forge->dropColumn('admin', 'remember_token');
    }
}
