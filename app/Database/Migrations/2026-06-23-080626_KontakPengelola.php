<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KontakPengelola extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'destinasi_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'unique' => true],
            'nomor_whatsapp'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'has_ticket_feature' => ['type' => 'BOOLEAN', 'default' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('destinasi_id', 'destinasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kontak_pengelola');
    }

    public function down()
    {
        $this->forge->dropTable('kontak_pengelola');
    }
}
