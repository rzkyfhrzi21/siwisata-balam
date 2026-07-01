<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DestinasiFasilitasMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'destinasi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'fasilitas_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true]
        ]);
        
        $this->forge->addForeignKey('destinasi_id', 'destinasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('fasilitas_id', 'fasilitas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('destinasi_fasilitas');
    }

    public function down()
    {
        $this->forge->dropTable('destinasi_fasilitas');
    }
}
