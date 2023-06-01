<?php 


namespace App\Traits;



trait ValidacoesTrait
{

    public function consultaViaCep(string $cep) : array
    {
        $cep = str_replace('-', '', $cep);

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $reposta = curl_exec($ch);

        $erro = curl_error($ch);

        $retorno = [];

        if($erro){

            $retorno['erro'] = $erro;

            return $retorno;
        }


        $consulta = json_decode($reposta);

        if(isset($consulta->erro) && !isset($consulta->cep)){

            session()->set('blockCep', true);

            $retorno['erro'] = '<span class="text-danger">Informe um CEP Válido!</span>';

            return $retorno;
        }

        
        session()->set('blockCep', false);

        $retorno['endereco'] = esc($consulta->logradouro);
        $retorno['cidade'] = esc($consulta->localidade);
        $retorno['bairro'] = esc($consulta->bairro);
        $retorno['uf'] = esc($consulta->uf);

        return $retorno;

    }
}