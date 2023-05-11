<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Grupo;

class Grupos extends BaseController
{

    private $grupoModel;

    public function __construct() 
    {
        $this->grupoModel = new \App\Models\GrupoModel();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Lista de Grupos',
        ];

        return view('Grupos/index', $data);
    }


    public function recuperagrupos()
    {

        if(!$this->request->isAJAX()){

            return redirect()->back();
        }
        
        $atributos = [
            'id',
            'nome',
            'descricao',
            'exibir',
            'data_exclusao',
        ];    
        $grupos = $this->grupoModel->select($atributos)
                                       ->withDeleted(true)
                                       ->orderBy('id', 'DESC')
                                       ->findAll();


        //Receberá o array de objetos de Usuários
        $data = [];
        foreach($grupos as $grupo){

     
            $data[] = [
                'nome' => anchor("grupos/exibir/$grupo->id", esc($grupo->nome), 'title="Exibir Grupo '.esc($grupo->nome).' "'),
                'descricao' => esc($grupo->descricao),
                'exibir' => $grupo->exibeSituacao(),
            ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);
    }


     //Funcao Exibir
     public function exibir(int $id = null)
     {
         //Validando o Usuário
         $grupo = $this->buscaGrupoOu404($id);
         
         $data = [
             'titulo' => "Dados do Grupo ".esc($grupo->nome),
             'grupo' => $grupo,
 
         ];
         return view('Grupos/exibir', $data);
 
     }


      //Metodo que Busca o Grupo
    private function buscaGrupoOu404(int $id = null)
    {

        if(! $id || !$grupo = $this->grupoModel->withDeleted(true)->find($id)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Grupo não encontrado $id");
        }

        return $grupo;
    }
}
