<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
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
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
            ],
            'reset_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null' => true,
                'default' => null,
            ],
            'reset_expira' => [
                'type'       => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'imagem' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
                'null' => true,
                'default' => null,
            ],
            'ativo' => [
                'type'       => 'BOOLEAN',
                'null' => false,
            ],
            'data_cadastro' => [
                'type'       => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'data_alteracao' => [
                'type'       => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'data_exclusao' => [
                'type'       => 'DATETIME',
                'null' => true,
                'default' => null,
            ],      
        ]);

        $this->forge->addkey('id', true);
        $this->forge->addUniquekey('email');

        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
