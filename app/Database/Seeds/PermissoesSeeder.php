<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissoesSeeder extends Seeder
{
    public function run()
    {

        $permissoesModel = new \App\Models\PermissoesModel();

        $permissoes = [
            [
                'nome' => 'listar-usuarios',
            ],
            [
                'nome' => 'criar-usuarios',
            ],
            [
                'nome' => 'editar-usuarios',
            ],
            [
                'nome' => 'excluir-usuarios',
            ],
        ];

        foreach($permissoes as $permissao){
            $permissoesModel->protect(false)->insert($permissao);
        }

        echo "Permissões Criadas com Sucesso!";
    }
}
