<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $returnType       = 'App\Entities\Usuario';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'nome',
        'email',
        'password',
        'reset_hash',
        'reset_expira',
        'imagem',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'data_cadastro';
    protected $updatedField  = 'data_alteracao';
    protected $deletedField  = 'data_exclusao';

    // Validation
    protected $validationRules = [
        'nome'                  => 'required|min_length[3]|max_length[100]',
        'email'                 => 'required|valid_email|max_length[240]|is_unique[usuarios.email,id,{id}]',
        'password'              => 'required|min_length[4]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required'   => 'O Campo nome é Obrigatório!',
            'min_length' => 'O Campo Nome Precisa ter no Minimo 3 Caracteres!',
        ],
        'email' => [
            'required'  => 'O Campo Email é Obrigatório!',
            'is_unique' => 'Email já Cadastrado no Sistema!',
        ],
        'password_confirmation' => [
            'required_with'  => 'Por favor Confirme a Senha!',
            'matches' => 'As senhas Precisam ser Iguais!',
        ],
    ];

    // Callbacks
    protected $beforeInsert         = ['hashPassword'];
    protected $beforeUpdate         = ['hashPassword'];
    //protected $afterUpdate    = [];
    //protected $beforeFind     = [];
    //protected $afterFind      = [];
    //protected $beforeDelete   = [];
    //protected $afterDelete    = [];


    //Password Hash
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])){

            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }
        return $data;
    }


    /**
     * Metodo que recupera o Usuario para logar na Aplicação
     * 
     * @param string $email
     * @return null|object
     */
    public function buscaUsuarioEmail(string $email)
    {
        return $this->where('email', $email)->where('data_exclusao', null)->first();
    }


    /**
     * Metodo que recupera as Permissões do Usuário Logado
     * 
     * @param integer $usuario_id
     * @return null|array
     */
    public function recuperaPermissoesDoUsuarioLogado(int $usuario_id)
    {

        $atributos = [
            //'usuarios.id',
            //'usuarios.nome AS usuario',
            //'grupos_usuarios.*',
            'permissoes.nome AS permissao',
        ];

        return $this->select($atributos)
                    ->asArray() // Recuperamos no formato array
                    ->join('grupos_usuarios', 'grupos_usuarios.usuario_id = usuarios.id')
                    ->join('grupos_permissoes', 'grupos_permissoes.grupo_id = grupos_usuarios.grupo_id')
                    ->join('permissoes', 'permissoes.id = grupos_permissoes.permissao_id')
                    ->where('usuarios.id', $usuario_id)
                    ->groupBy('permissoes.nome')
                    ->findAll();
    }

}
