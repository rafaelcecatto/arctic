<?php

namespace App\Models;

use CodeIgniter\Model;

class FornecedorModel extends Model
{
    protected $table            = 'fornecedores';
    protected $returnType       = 'App\Entities\Fornecedor';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'razao',
        'fantasia',
        'cnpj',
        'ie',
        'cep',
        'endereco',
        'numero',
        'cidade',
        'bairro',
        'uf',
        'telefone',
        'celular',
        'email',
        'ativo',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'data_cadastro';
    protected $updatedField  = 'data_alteracao';
    protected $deletedField  = 'data_exclusao';

    // Validation
    protected $validationRules = [
        'razao'                 => 'required|min_length[3]|max_length[128]|is_unique[fornecedores.razao,id,{id}]',
        'fantasia'              => 'required|min_length[3]|max_length[128]',
        'cnpj'                  => 'required|validaCNPJ|max_length[25]|is_unique[fornecedores.cnpj,id,{id}]',
        'ie'                    => 'required|max_length[25]|is_unique[fornecedores.ie,id,{id}]',
        'cep'                   => 'required|max_length[9]|',
        'endereco'              => 'required|min_length[3]|max_length[240]',
        'numero'                => 'max_length[45]|',
        'cidade'                => 'required|max_length[80]|',
        'bairro'                => 'required|max_length[80]|',
        'uf'                    => 'required|max_length[2]|',
        'telefone'              => 'required|max_length[20]|is_unique[fornecedores.telefone,id,{id}]',
        'celular'               => 'max_length[20]|',
        'email'                 => 'required|valid_email|max_length[240]|is_unique[fornecedores.email,id,{id}]',   
    ];
    protected $validationMessages = [
        'razao' => [
            'required'   => 'O Campo nome é Obrigatório!',
            'min_length' => 'O Campo Nome Precisa ter no Minimo 3 Caracteres!',
        ],
        'cnpj' => [
            'required'  => 'O Campo CNPJ é Obrigatório!',
            'is_unique' => 'CNPJ já Cadastrado no Sistema!',
        ],
    ];
}
