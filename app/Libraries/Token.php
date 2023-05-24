<?php

namespace App\Libraries;

class Token{

    private $token;


    /**
     * Método Para Recuperar o Token Hash 
     * 
     * @param string $token
     */
    public function __construct(string $token = null)
    {
        if($token === null){

            $this->token = bin2hex(random_bytes(16));

        }else{

            $this->token = $token;

        }
    }


    /**
     * Metodo que Retorna o Valor do $token
     * 
     * @return string
     */
    public function getValue() : string
    {
        return $this->token;
    }


    
    public function getHash() : string
    {
        return hash_hmac("sha256", $this->token, getenv('CHAVE_RECUPERA_SENHA'));
    }

}