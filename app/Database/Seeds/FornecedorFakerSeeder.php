<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FornecedorFakerSeeder extends Seeder
{
    public function run()
    {
        $fornecedorModel = new \App\Models\FornecedorModel();

        $faker = \Faker\Factory::create('pt-BR');

        $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\PhoneNumber($faker));
        
        $criarFornecedores = 2500;

        $fornecedoresPush = [];

        for($i = 0; $i < $criarFornecedores; $i++){

            array_push($fornecedoresPush, [
                'razao' => $faker->unique()->company,
                'fantasia' => $faker->name,
                'cnpj' => $faker->unique()->cnpj,
                'ie' => $faker->unique()->numberBetween(100000000000, 900000000000),
                'cep' => $faker->postcode,
                'endereco' => $faker->streetName,
                'numero' => $faker->buildingNumber,
                'cidade' => $faker->city,
                'bairro' => $faker->city,
                'uf' => $faker->stateAbbr,
                'telefone' => $faker->unique()->cellphoneNumber,
                'celular' => $faker->unique()->cellphoneNumber,
                'email' => $faker->unique()->email,
                'ativo' => $faker->numberBetween(0, 1),    
                'data_cadastro' => $faker->dateTimeBetween('-2 month', '-1 days')->format('Y-m-d H:i:s'), 
                'data_alteracao' => $faker->dateTimeBetween('-2 month', '-1 days')->format('Y-m-d H:i:s'),  
            ]);
        }

        
        $fornecedorModel->skipValidation(true)->insertBatch($fornecedoresPush);
    }
}
