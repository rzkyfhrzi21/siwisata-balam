<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Relasi ke tabel user/admin',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'Nama lengkap/label data',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
                'comment'    => 'Alamat IP',
            ],
            'activity' => [
                'type'    => 'TEXT',
                'comment' => 'Aktivitas yang dilakukan',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'Waktu atau tanggal created',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id'); // Menambahkan index pada user_id
        $this->forge->createTable('activity_logs');
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs');
    }
}
