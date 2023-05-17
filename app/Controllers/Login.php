<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function novo()
    {
        $data = [
            'titulo' => 'Faça o Login',
        ];

        return view('Login/novo', $data);
    }


    public function criar()
    {

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        //Recuperando a instância do serviço Autenticação
        $autenticacao = service('autenticacao');


        if ($autenticacao->login($email, $password) === false) {

            //Credenciais Invalidas
            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['credenciais' => 'Senha ou Usuário Inválidos!'];
            return $this->response->setJSON($retorno);
        }

        //Credenciais Validas

        //Recupero o Usuário Logado
        $usuarioLogado = $autenticacao->pegaUsuarioLogado();

        session()->setFlashdata('sucesso', "Bem-Vindo $usuarioLogado->nome");

        if ($usuarioLogado->is_cliente) {
            $retorno['redirect'] = 'ordens/minhas';
            return $this->response->setJSON($retorno);
        }

        //Usuário Normal
        $retorno['redirect'] = 'home';
        return $this->response->setJSON($retorno);
    }


    public function logout()
    {

        $autenticacao = service('autenticacao');

        $usuarioLogado = $autenticacao->pegaUsuarioLogado();

        $autenticacao->logout();

        return redirect()->to(site_url("login/mostramensagemlogout/$usuarioLogado->nome"));

    }


    public function mostraMensagemLogout($nome = null)
    {

        return redirect()->to(site_url("login"))->with("sucesso", "$nome, esperamos ver você novamente!");

    }
}
