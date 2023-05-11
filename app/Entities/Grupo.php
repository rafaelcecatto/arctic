<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Grupo extends Entity
{
    
    protected $dates   = ['data_cadastro', 'data_alteracao', 'data_exclusao'];


    public function exibeSituacao()
    {

        if($this->data_exclusao != null){

            $icone = '<span class="text-white">Excluído</span>&nbsp<i class="fa fa-undo"></i>&nbsp;Desfazer';

            $situacao = anchor("grupos/restaurarusuario/$this->id", $icone, ['class' => 'btn btn-outline-succes btn-sm']);

            return $situacao;
        }

      
        if($this->exibir == true){
            
            return '<i class="fa fa-eye text-secondary"></i>&nbsp;Exibir Grupo';
        }

        if($this->exibir == false){
            
            return '<i class="fa fa-eye-slash text-danger"></i>&nbsp;Não Exibir';
        }
    }
    
}
