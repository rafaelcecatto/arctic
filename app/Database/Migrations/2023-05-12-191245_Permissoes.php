<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permissoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],    
        ]);

        $this->forge->addkey('id', true);
        $this->forge->addUniquekey('nome');

        $this->forge->createTable('permissoes');
    }

    public function down()
    {
        $this->forge->dropTable('permissoes');
    }
}
