<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Traits\ValidacoesTrait;
use App\Entities\Fornecedor;

class Fornecedores extends BaseController
{
    use ValidacoesTrait;

    private $fornecedorModel;

    public function __construct()
    {
        $this->fornecedorModel = new \App\Models\FornecedorModel();
    }


    public function index()
    {
        $data = [
            'titulo' => 'Listando Fornecedor',
        ];

        return view('Fornecedores/index', $data);
    }


    public function recuperaFornecedores()
    {


        if(!$this->request->isAJAX()){

            return redirect()->back();
        }
        
        $atributos = [
            'id',
            'razao',
            'fantasia',
            'cnpj',
            'telefone',
            'ativo',
            'data_exclusao',
        ];    
        $fornecedores = $this->fornecedorModel->select($atributos)
                                       ->withDeleted(true)
                                       ->orderBy('id', 'DESC')
                                       ->findAll();

        $data = [];
        foreach($fornecedores as $fornecedor){


            $data[] = [
                'razao' => anchor("fornecedores/exibir/$fornecedor->id", esc($fornecedor->razao), 'title="Exibir Fornecedor '.esc($fornecedor->razao).' "'),
                'fantasia' => esc($fornecedor->fantasia),
                'cnpj' => esc($fornecedor->cnpj),
                'telefone' => esc($fornecedor->telefone),
                'ativo' => $fornecedor->exibeSituacao(),
            ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);
    }


    public function criar()
    {
    
        $fornecedor = new Fornecedor();
        
        $data = [
            'titulo' => "Cadastrar novo Fornecedor ",
            'fornecedor' => $fornecedor,

        ];
        return view('Fornecedores/criar', $data);

    }


    public function cadastrar()
    {
        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        $retorno['token'] = csrf_hash();

        if(session()->get('blockCep') === true){
            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['cep' => 'Informe um CEP Válido!'];

            return $this->response->setJSON($retorno);
        }

        $post = $this->request->getPost();

        $fornecedor = new Fornecedor($post);


        if($this->fornecedorModel->save($fornecedor)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            $retorno['id'] = $this->fornecedorModel->getInsertID();

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->fornecedorModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    public function exibir(int $id = null)
    {
        //Validando o Usuário
        $fornecedor = $this->buscaFornecedorOu404($id);
        
        $data = [
            'titulo' => "Dados do Fornecedor ".esc($fornecedor->nome),
            'fornecedor' => $fornecedor,

        ];
        return view('Fornecedores/exibir', $data);

    }


    public function editar(int $id = null)
    {
        //Validando o Usuário
        $fornecedor = $this->buscaFornecedorOu404($id);
        
        $data = [
            'titulo' => "Editando o Fornecedor ".esc($fornecedor->razao),
            'fornecedor' => $fornecedor,

        ];
        return view('Fornecedores/editar', $data);

    }


    public function atualizar()
    {
        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        $retorno['token'] = csrf_hash();

        if(session()->get('blockCep') === true){
            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['cep' => 'Informe um CEP Válido!'];

            return $this->response->setJSON($retorno);
        }

        $post = $this->request->getPost();

        $fornecedor = $this->buscaFornecedorOu404($post['id']);

        $fornecedor->fill($post);

        if($fornecedor->hasChanged() === false){

            $retorno['info'] = 'Não há Dados para Atualizar!';
            return $this->response->setJSON($retorno);
        }

        if($this->fornecedorModel->save($fornecedor)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->fornecedorModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }



    public function excluir($id = null)
    {
        $fornecedor = $this->buscaFornecedorOu404($id);

        if($fornecedor->data_exclusao != null){

            return redirect()->back()->with('info', "Fornecedor $fornecedor->razao já está excluído!");
        }

        if($this->request->getMethod() === 'post'){

            $this->fornecedorModel->delete($id);

            return redirect()->to(site_url("fornecedores"))->with('sucesso', "Fornecedor $fornecedor->razao excluído com sucesso!");
        }


        $data = [
            'titulo' => "Excluindo o Fornecedor ".esc($fornecedor->razao),
            'fornecedor' => $fornecedor,

        ];
        return view('Fornecedores/excluir', $data);
    }


    
    public function consultaCep()
    {
        if(!$this->request->isAJAX()){

            return redirect()->back();
        }


        $cep = $this->request->getGet('cep');

        return $this->response->setJSON($this->consultaViaCep($cep));

    }



    /**
     * Método que Recupera o Fornecedor
     * 
     * @param integer $id
     * @return Exceptions|object
     */
    private function buscaFornecedorOu404(int $id = null)
    {

        if(! $id || !$fornecedor = $this->fornecedorModel->withDeleted(true)->find($id)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Fornecedor não encontrado $id");
        }

        return $fornecedor;
    }
}
