<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioFakerSeeder extends Seeder
{
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel();

        $faker = \Faker\Factory::create();
        
        $criarUsuarios = 5000;

        $usuariosPush = [];

        for($i = 0; $i < $criarUsuarios; $i++){

            array_push($usuariosPush, [
                'nome' => $faker->unique()->name,
                'email' => $faker->unique()->email,
                'password_hash' => '123456', // Alterar o Fake  quando conhecermos Hash
                'ativo' => $faker->numberBetween(0, 1), // True ou False
            ]);
        }

        //echo '<pre>';
        //print_r($usuariosPush);
        //exit;

        $usuarioModel->skipValidation(true) //bypass na validação
                     ->protect(false) // bypass na proteção dos Campos allwedFields 
                     ->insertBatch($usuariosPush);

        echo "$criarUsuarios usuários criados com sucesso!";
    }
}
