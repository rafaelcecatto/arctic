<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Grupos extends Migration
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
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
            ],
            'exibir' => [ // se o grupo estiver como true, ele será exibido opção na hora de definir um responsavel tecnico pela ordem
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
        $this->forge->addUniquekey('nome');

        $this->forge->createTable('grupos');
    }

    public function down()
    {
        $this->forge->dropTable('grupos');
    }
}
