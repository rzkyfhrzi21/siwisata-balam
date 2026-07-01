<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIconToFasilitas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fasilitas', [
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'nama_fasilitas'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', 'icon');
    }
}
