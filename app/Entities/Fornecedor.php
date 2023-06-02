<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Fornecedor extends Entity
{
    protected $datamap = [];
    protected $dates   = ['data_cadastro', 'data_alteracao', 'data_exclusao'];
    protected $casts   = [];


    public function exibeSituacao()
    {

        if($this->data_exclusao != null){

            $icone = '<span class="text-white">Excluído</span>&nbsp<i class="fa fa-undo"></i>&nbsp;Desfazer';

            $situacao = anchor("fornecedores/restaurarfornecedor/$this->id", $icone, ['class' => 'btn btn-outline-succes btn-sm']);

            return $situacao;
        }

      
        if($this->ativo == true){
            
            return '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo';
        }

        if($this->ativo == false){
            
            return '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo';
        }
    }
}
