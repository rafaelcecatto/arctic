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


    public function processaesqueci()
    {
        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        // Recupero o email da requisição
        $email = $this->request->getPost('email');

       
    }
}
