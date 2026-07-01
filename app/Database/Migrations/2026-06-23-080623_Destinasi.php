<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Destinasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_wisata'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'deskripsi'        => ['type' => 'TEXT', 'null' => true],
            'alamat'           => ['type' => 'TEXT', 'null' => true],
            'jam_operasional'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'hari_operasional' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tiket'            => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'fasilitas'        => ['type' => 'TEXT', 'null' => true],
            'aturan'           => ['type' => 'TEXT', 'null' => true],
            'latitude'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'longitude'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'thumbnail'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_wisata', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('destinasi');
    }

    public function down()
    {
        $this->forge->dropTable('destinasi');
    }
}
