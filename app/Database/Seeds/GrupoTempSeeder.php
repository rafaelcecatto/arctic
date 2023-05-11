<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GrupoTempSeeder extends Seeder
{
    public function run()
    {

        $grupoModel = new \App\Models\GrupoModel();

        $grupos = [
            [
                'nome' => 'Administrador',
                'descricao' => 'Grupo com Acesso total ao Sistema',
                'exibir' => false,
            ],

            [
                'nome' => 'Clientes',
                'descricao' => 'Grupo com Acesso Somente para Visualizar as Ordens de Serviço',
                'exibir' => false,
            ],

            [
                'nome' => 'Atendente',
                'descricao' => 'Grupo com Acesso para Atendimento aos Clientes',
                'exibir' => false,
            ],
        ];

        foreach($grupos as $grupo){
            $grupoModel->insert($grupo);
        }

        echo "Grupos criados com sucesso!";
    }
}
