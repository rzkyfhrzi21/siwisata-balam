<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToFasilitas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fasilitas', [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'nama_fasilitas'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', 'slug');
    }
}
