<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'Primary Key',
            ],
            'kunci' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Nama kunci pengaturan (mis. hero_index_1, hero_umum)',
            ],
            'nilai' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Nilai pengaturan (nama file gambar)',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'Waktu terakhir diubah',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kunci');
        $this->forge->createTable('pengaturan');
    }

    public function down()
    {
        $this->forge->dropTable('pengaturan');
    }
}
