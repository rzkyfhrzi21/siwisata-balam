<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinkGmapsToDestinasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('destinasi', [
            'link_gmaps' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'longitude'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('destinasi', 'link_gmaps');
    }
}
