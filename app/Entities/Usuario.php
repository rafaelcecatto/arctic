<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

use App\Libraries\Token;

class Usuario extends Entity
{
    
    protected $dates   = ['data_cadastro', 'data_alteracao', 'data_exclusao'];
    protected $casts   = [];

    public function exibeSituacao()
    {

        if($this->data_exclusao != null){

            $icone = '<span class="text-white">Excluído</span>&nbsp<i class="fa fa-undo"></i>&nbsp;Desfazer';

            $situacao = anchor("usuarios/restaurarusuario/$this->id", $icone, ['class' => 'btn btn-outline-succes btn-sm']);

            return $situacao;
        }

      
        if($this->ativo == true){
            
            return '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo';
        }

        if($this->ativo == false){
            
            return '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo';
        }
    }


    /**
     * Metodo que verifica se a senha é válida
     * 
     * @param string $password
     * @return boolean
     */
    public function verificaPassword(string $password): bool
    {

        return password_verify($password, $this->password_hash);

    }


    /**
     * Metodo que Válida se o Usuario logado possui permissão acessar determindas Rotas
     * 
     * @param string $permissao
     * @return boolean
     */
    public function temPermissaoPara(string $permissao) : bool
    {

        //Se Usuario for admin, retorna True
        if($this->is_admin == true){
            return true;
        }

        //Verifica se o usuario tem permissões
        if(empty($this->permissoes)){
            return false;
        }


        // Verica as Permissoes que o usuario Tem
        if(in_array($permissao, $this->permissoes) == false){
            return false;
        }

        //Retorna True pois a Permissão é Valida
        return true;

    }


    /**
     * Método que inicia a Recuperação de Senha
     * 
     * @return void
     */
    public function iniciaPasswordReset() : void
    {

        $token = new Token();

        $this->reset_token = $token->getValue();

        $this->reset_hash = $token->getHash();

        $this->reset_expira = date('Y-m-d H:i:s', time() + 7200);
    }
}
