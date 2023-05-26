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

        /**
         * @todo Enviar e-mail de recuperação
         */

        
        return $this->response->setJSON([]);
            
    }



    public function resetEnviado()
    {
        $data = [
            'titulo' => 'E-mail enviado com Sucesso!',
        ];

        return view('Password/reset_enviado', $data);
    }
}
