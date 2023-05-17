<?php

namespace App\Libraries;


class Autenticacao
{

    private $usuario;
    private $usuarioModel;
    private $grupoUsuarioModel;


    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->grupoUsuarioModel = new \App\Models\GrupoUsuarioModel();
    }


    /**
     * Metodo que realiza o Login na Aplicação
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login(string $email, string $password): bool
    {
        //Busca o Usuário
        $usuario = $this->usuarioModel->buscaUsuarioEmail($email);

        if($usuario === null){
            return false;
        }

        //Verifica se a Senha é Válida
        if($usuario->verificaPassword($password) == false){
            return false;
        }

        //Verifica se o usuário está ativo
        if($usuario->ativo == false){
            return false;
        }

        //Logamos o Usuário na Aplicação
        $this->logaUsuario($usuario);



        return true;

    }


    /**
     * Metodo de Logout
     * 
     * @return void
     */
    public function logout(): void
    {
        session()->destroy();
    }


    /**
     *Metodo que pega o usuario Logado
     * 
     * 
     */
    public function pegaUsuarioLogado()
    {

        if($this->usuario === null){

           $this->usuario = $this->pegaUsuarioSecao();
        }

        return $this->usuario;

    }


    /**
     * Metodo que Verifica se o Usuário está Logado
     * 
     * @return boolean 
     */
    public function estaLogado() : bool
    {
        return $this->pegaUsuarioLogado() !== null;
    }

    //-----------------------------Metodos Privados----------------------------//

    /**
     * Método que insere na Seção o ID do Usuário
     * 
     * @param object $usuario
     * @return void
     */
    private function logaUsuario(object $usuario): void
    {
        //Recupera Instancia da Seção
        $session = session();

        //Antes de Inserir Id do usuario na Seção. devemos gerar um novo ID da seção
        $_SESSION['__ci_last_regenerate'] = time();

        //Setamos na Seção o ID do Usuário
        $session->set('usuario_id', $usuario->id);
    }


     /**
     * Metodo que recupera da seção e valida o Usuário Logado
     * 
     * @return null|object
     */
    private function pegaUsuarioSecao()
    {
        if(session()->has('usuario_id') == false){
            return null;
        }

        // Busca Usuário na Base de Dados
        $usuario = $this->usuarioModel->find(session()->get('usuario_id'));

        //Valida se o Usuario existe e se tem permissão de login na aplicação
        if($usuario == null || $usuario->ativo == false ){
            return null;
        }

        //definimos as Perissoes do Usuario Logado
        $usuario = $this->definePermissoesDoUsuarioLogado($usuario);


        //Retorna o objeto do Usuário
        return $usuario;
    }


    /**
     * Metodo que Verifica se o Usuário é um Admin session()->get('usuario_id').
     * 
     * @return boolean
     */
    private function isAdmin() : bool
    {
        //Definimos o id do Grupo Admin.
        //Esse ID jamais poderá ser alterado
        $grupoAdmin = 1;

        //Verifica se o Usuario logado está no Grupo Admin.
        $administrador = $this->grupoUsuarioModel->usuarioEstaNoGrupo($grupoAdmin, session()->get('usuario_id'));

        //Verifica se foi encontrado o Registro.
        if($administrador == null){

            return false;
        }

        //Retorna true, usuario logado faz parte do grupo Admin.
        return true;

    }


    /**
     * Metodo que Verifica se o Usuário é um Cliente session()->get('usuario_id').
     * 
     * @return boolean
     */
    private function isCliente() : bool
    {
        //Definimos o id do Grupo Admin.
        //Esse ID jamais poderá ser alterado
        $grupoCliente = 2;

        //Verifica se o Usuario logado está no Grupo Admin.
        $cliente = $this->grupoUsuarioModel->usuarioEstaNoGrupo($grupoCliente, session()->get('usuario_id'));

        //Verifica se foi encontrado o Registro.
        if($cliente == null){

            return false;
        }

        //Retorna true, usuario logado faz parte do grupo Admin.
        return true;

    }


    /**
     * Metodo que define as Permissoes que o usuario Logado Possui
     * Usado exclusivamente No Metodo pegaUsuarioDaSecao()
     * 
     * @param object $usuario
     * @return object
     */
    private function definePermissoesDoUsuarioLogado(object $usuario) : object
    {

        //Definimos se o usuario logado é Admin
        $usuario->is_admin = $this->isAdmin();

        //Se for admin, não é cliente
        if($usuario->is_admin == true){

            $usuario->is_cliente = false;

        }else{

            $usuario->is_cliente = $this->isCliente();
        }


        
        if($usuario->is_admin == false && $usuario->is_cliente == false){

            $usuario->permissoes = $this->recuperaPermissoesDoUsuarioLogado();

        }

        return $usuario;
    }


    /**
     * Matodo que Retorna as permissoes do Usuario LOgado
     * 
     * @return array
     */
    private function recuperaPermissoesDoUsuarioLogado() : array
    {

        $permissoesDoUsuario = $this->usuarioModel->recuperaPermissoesDoUsuarioLogado( session()->get('usuario_id'));

        return array_column($permissoesDoUsuario, 'permissao');
    }


}