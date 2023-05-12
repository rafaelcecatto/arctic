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


    public function recuperaGrupos()
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

    //Funcao Criar
    public function criar(int $id = null)
    {
          //Validando o Usuário
          $grupo = new Grupo();

          $data = [
              'titulo' => "Cadastrar o Grupo",
              'grupo' => $grupo,
  
          ];
          return view('Grupos/criar', $data);
  
    }


    //Funcao Cadastrar
    public function cadastrar()
    {

        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        // Recupero o Post da requisição
        $post = $this->request->getPost();

  
        // Cria novo Objeto da entidade Usuario
        $grupo = new Grupo($post);


        if($this->grupoModel->save($grupo)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            $retorno['id'] = $this->grupoModel->getInsertID();
            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->grupoModel->errors();

        //Retorno para o Ajax Request
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

     
    //Funcao Editar
    public function editar(int $id = null)
    {
          //Validando o Usuário
          $grupo = $this->buscaGrupoOu404($id);

          if($grupo->id < 3){

            return redirect()->back()->with('atencao', 'O Grupo <b>' .esc($grupo->nome). '</b> Não Pode ser Editado nem Excluído!');
          }
          
          $data = [
              'titulo' => "Editar o Grupo ".esc($grupo->nome),
              'grupo' => $grupo,
  
          ];
          return view('Grupos/editar', $data);
  
    }


    //Funcao Atualizar
    public function atualizar()
    {

        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        // Recupero o Post da requisição
        $post = $this->request->getPost();

  
        //Validando o Usuário
        $grupo = $this->buscaGrupoOu404($post['id']);

            
            //Evita Manipulação de Formulário
            if($grupo->id < 3){

            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['O Grupo <b class="text-white">' .esc($grupo->nome). '</b> Não Pode ser Editado nem Excluído!'];
            return $this->response->setJSON($retorno);
          }

       
        //Preenchemos os Atributos dos Usuários com os Valores do POST
        $grupo->fill($post);

        if($grupo->hasChanged() == false){
            $retorno['info'] = 'Não a Dados para Serem Atualizados';
            return $this->response->setJSON($retorno);
        }


        if($this->grupoModel->protect(false)->save($grupo)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->grupoModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    //Funcao Excluir
    public function excluir(int $id = null)
    {
        //Validando o Usuário
        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->id < 3){

            return redirect()->back()->with('atencao', 'O Grupo <b>' .esc($grupo->nome). '</b> Não Pode ser Editado nem Excluído!');
          }

        if($grupo->data_exclusao != null){

            return redirect()->back()->with('info', "Esse Grupo já foi Excluído!");

        }

        if($this->request->getMethod() === 'post'){
            //Excluie o Grupo
            $this->grupoModel->delete($grupo->id);
            

            return redirect()->to(site_url("grupos"))->with('sucesso', 'Grupo'.esc($grupo->nome).' excluído com Sucesso!');

        }
        
        $data = [
            'titulo' => "Excluir Grupo ".esc($grupo->nome),
            'grupo' => $grupo,

        ];
        return view('Grupos/excluir', $data);

    }


    //Funcao Restaurar Usuário
    public function restaurarGrupo(int $id = null)
    {
         //Validando o Usuário
         $grupo = $this->buscaGrupoOu404($id);
 
         if($grupo->data_exclusao == null){
             return redirect()->back()->with('info', "Grupo não está Excluído!");
         }

         $grupo->data_exclusao = null;
         $this->grupoModel->protect(false)->save($grupo);
 
         return redirect()->back()->with('sucesso', 'Grupo'.esc($grupo->nome).' Recuperado com Sucesso!');
    }


    //Funcao Permissoes
    public function permissoes(int $id = null)
    {
        //Validando o Usuário
        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->id < 3){
            return redirect()->back()->with('info', 'O Grupo <b>' .esc($grupo->nome). '</b> Não Precisa de definição de Acessos!');
        }


         
         $data = [
             'titulo' => "Gerenciar as Permissoes de Acesso do ".esc($grupo->nome),
             'grupo' => $grupo,
 
         ];
         return view('Grupos/permissoes', $data);
 
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
