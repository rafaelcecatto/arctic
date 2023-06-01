<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Fornecedores extends Migration
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
            'razao' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'unique' => true,
            ],
            'fantasia' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true,
            ],
            'ie' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'cep' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
            ],
            'endereco' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'bairro' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'uf' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'telefone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'celular' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
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

        $this->forge->createTable('fornecedores');
    }

    public function down()
    {
        $this->forge->dropTable('fornecedores');
    }
}
