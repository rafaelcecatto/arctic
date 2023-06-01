<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Password extends BaseController
{

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }


    public function esqueci()
    {
        $data = [
            'titulo' => 'Esqueci a Minha Senha',
        ];

        return view('Password/esqueci', $data);
    }

    

    public function processaEsqueci()
    {
        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        // Recupero o email da requisição
        $email = $this->request->getPost('email');


        $usuario = $this->usuarioModel->buscaUsuarioEmail($email);

        if($usuario === null || $usuario->ativo === false){

            $retorno['erro'] = 'Não Encontramos uma Conta Válido para esse E-mail!';
            return $this->response->setJSON($retorno);
        }

        $usuario->iniciaPasswordReset();

        $this->usuarioModel->save($usuario);

        $this->enviaEmailRecuperaSenha($usuario);
        
        return $this->response->setJSON([]);
            
    }



    public function resetEnviado()
    {
        $data = [
            'titulo' => 'E-mail enviado com Sucesso!',
        ];

        return view('Password/reset_enviado', $data);
    }



    public function reset($token = null)
    {

        if($token  === null){

            return redirect()->to(site_url("password/esqueci"))->with("atencao", "Link Inválido ou Expirado!");
        }

        //Buscar o Hash do Usuário na base para comparar o Reset
        $usuario = $this->usuarioModel->buscaHashUsuario($token);

        if($usuario === null){
            return redirect()->to(site_url("password/esqueci"))->with("atencao", "Link Inválido ou Expirado!");
        }


        $data = [
            'titulo' => 'Criar a Nova Senha',
            'token' => $token,
        ];

        return view('Password/reset', $data);

    }



    public function processaReset()
    {
        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        $post = $this->request->getPost();


        //Buscar o Hash do Usuário na base para comparar o Reset
        $usuario = $this->usuarioModel->buscaHashUsuario($post['token']);

        if($usuario === null){
            
            $retorno['erro'] = 'A Senha Digitada não é Válida!';
            $retorno['erros_model'] = ['link_invalido' => 'Link Inválido ou Expirado!'];
            return $this->response->setJSON($retorno);
        }

        $usuario->fill($post);

        $usuario->finalizaPasswordReset();

        if($this->usuarioModel->save($usuario)){
            
            session()->setFlashdata("sucesso", "Senha Alterada com Sucesso!");

            return $this->response->setJSON($retorno);

        }

        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        return $this->response->setJSON($retorno);
        
    }

    /**
     * Método que envia o email para o Usuário
     * 
     * @param object $usuario
     * @return void
     */
    private function enviaEmailRecuperaSenha(object $usuario) : void
    {
        
        $email = service('email');

        $email->setFrom('no-reply@arctic.com', 'Arctic Sistema');

        $email->setTo($usuario->email);
       
        $email->setSubject('Redefinir Senha');

        $data = [
            'token' => $usuario->reset_token
        ];

        $mensagem = view('Password/reset_email', $data);

        $email->setMessage($mensagem);

        $email->send(); 
    }
}
