<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupoModel extends Model
{
    
    protected $table            = 'grupos';
    protected $returnType       = 'App\Entities\Grupo';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nome', 'descricao', 'exibir'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'data_cadastro';
    protected $updatedField  = 'data_alteracao';
    protected $deletedField  = 'data_exclusao';

    // Validation
    protected $validationRules = [
        'nome'                  => 'required|min_length[3]|max_length[100]|is_unique[grupos.nome,id,{id}]',
        'descricao'             => 'required|min_length[3]|max_length[240]',
        
    ];
    protected $validationMessages = [
        'nome' => [
            'required'   => 'O Campo nome é Obrigatório!',
            'min_length' => 'O Campo Nome Precisa ter no Minimo 3 Caracteres!',
        ],
        'descricao' => [
            'required'  => 'O Campo Descrição é Obrigatório!',
            'min_length' => 'O Campo Descrição Precisa ter no Minimo 3 Caracteres!',       
        ],
        
    ];
}
