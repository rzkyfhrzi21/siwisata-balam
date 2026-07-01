<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Galeri extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'destinasi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tipe_media'   => ['type' => 'ENUM', 'constraint' => ['foto', 'video'], 'default' => 'foto'],
            'path_media'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('destinasi_id', 'destinasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('galeri');
    }

    public function down()
    {
        $this->forge->dropTable('galeri');
    }
}
