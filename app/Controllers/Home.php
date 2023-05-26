<?php

namespace App\Controllers;

use App\Libraries\Autenticacao;

class Home extends BaseController
{
    public function index()
    {

        $data = [
            'titulo' => 'Home'

        ];

        return view('Home/index', $data);
    }


    public function login()
    {

        $autenticacao = service('autenticacao');

        $autenticacao->login('rcecatto@icloud.com', '123456');

        $usuario = $autenticacao->pegaUsuarioLogado();

        dd($usuario);

        //dd($autenticacao->isCliente());
        //$autenticacao->logout();
        //return redirect()->to(site_url('/'));

        //dd($autenticacao->estaLogado());

    }



    public function email()
    {
        
        $email = service('email');

        $email->setFrom('no-reply@arctic.com', 'Arctic Sistema');
        $email->setTo('rcecatto@icloud.com');
       
        $email->setSubject('Recuperação de Senha');
        $email->setMessage('Segue o Processo para Recuperar Senha!');

        if($email->send()){

            echo 'E-mail Enviado!';

        }else{

            $email->printDebugger();

        }
      
    }
}
