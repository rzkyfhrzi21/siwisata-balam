<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriWisata extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_kategori' => ['type' => 'VARCHAR', 'constraint' => 50],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_wisata');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_wisata');
    }
}
