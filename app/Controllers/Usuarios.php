<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

    private $usuarioModel;
    private $grupoUsuarioModel;
    private $grupoModel;

    public function __construct() 
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->grupoUsuarioModel = new \App\Models\GrupoUsuarioModel();
        $this->grupoModel = new \App\Models\GrupoModel();
    }

    
    public function index()
    {
        $data = [
            'titulo' => 'Lista de Usuários',
        ];

        return view('Usuarios/index', $data);
    }


    public function recuperausuarios()
    {

        if(!$this->request->isAJAX()){

            return redirect()->back();
        }
        
        $atributos = [
            'id',
            'nome',
            'email',
            'ativo',
            'imagem',
            'data_exclusao',
        ];    
        $usuarios = $this->usuarioModel->select($atributos)
                                       ->withDeleted(true)
                                       ->orderBy('id', 'DESC')
                                       ->findAll();


        //Receberá o array de objetos de Usuários
        $data = [];
        foreach($usuarios as $usuario){

            //Definimos o caminho da imagem do usuário para index
            if($usuario->imagem != null){
                
                $imagem = [
                    'src' => site_url("usuarios/imagem/$usuario->imagem"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => esc($usuario->nome),
                    'width' => '30',
                ];
                
            }else {
                //Não tem Imagem
                $imagem = [
                    'src' => site_url("recursos/img/usuario_sem_imagem.png"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => 'Usuário sem Imagem',
                    'width' => '30',
                ];
            }

            $data[] = [
                'imagem' => $usuario->imagem = img($imagem),
                'nome' => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title="Exibir usuário '.esc($usuario->nome).' "'),
                'email' => esc($usuario->email),
                'ativo' => $usuario->exibeSituacao(),
            ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);
    }


    //Funcao Criar
    public function criar()
    {
        
          //Validando o Usuário
          $usuario = new Usuario();
          
          $data = [
              'titulo' => "Cadastrar Usuário",
              'usuario' => $usuario,
  
          ];
          return view('Usuarios/criar', $data);
  
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
        $usuario = new Usuario($post);


        if($this->usuarioModel->protect(false)->save($usuario)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    //Funcao Exibir
    public function exibir(int $id = null)
    {
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data = [
            'titulo' => "Dados do Usuário ".esc($usuario->nome),
            'usuario' => $usuario,

        ];
        return view('Usuarios/exibir', $data);

    }


    //Funcao Editar
    public function editar(int $id = null)
    {
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data = [
            'titulo' => "Editar o Usuário ".esc($usuario->nome),
            'usuario' => $usuario,

        ];
        return view('Usuarios/editar', $data);

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
        $usuario = $this->buscaUsuarioOu404($post['id']);

        //Verifca se a senha está vindo do POST
        if(empty($post['password'])){

            unset($post['password']);
            unset($post['password_confirmation']);
        }


        //Preenchemos os Atributos dos Usuários com os Valores do POST
        $usuario->fill($post);

        if($usuario->hasChanged() == false){
            $retorno['info'] = 'Não a Dados para Serem Atualizados';
            return $this->response->setJSON($retorno);
        }


        if($this->usuarioModel->protect(false)->save($usuario)){

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    //Funcao Editar Imagem
    public function editarImagem(int $id = null)
    {
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data = [
            'titulo' => "Editar a Imagem ".esc($usuario->nome),
            'usuario' => $usuario,

        ];
        return view('Usuarios/editar_imagem', $data);

    }


    //Funcao Upload
    public function upload()
    {

        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        //Validação da Imagem
        $validacao = service('validation');

        $regras = [
            'imagem' => 'uploaded[imagem]|max_size[imagem,1024]|ext_in[imagem,png,jpg,jpeg,webp]',
        ];

        $mensagens = [   // Errors
            'imagem' => [
                'uploaded' => 'Nenhuma Imagem Selecionada!',
                'ext_in' => 'Extenção Inválida! Use .png, .jpg, .jpeg, ou .webp!',
            ],
        ];

        $validacao->setRules($regras, $mensagens);

        if($validacao->withRequest($this->request)->run() == false){

             $retorno['erro'] = 'Verifique os Dados';
             $retorno['erros_model'] = $validacao->getErrors();

             return $this->response->setJSON($retorno);
        }


        // Recupero o Post da requisição
        $post = $this->request->getPost();
  
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);

        //Recupereamos a Imagem que veio do POST
        $imagem = $this->request->getFile('imagem');

        list($largura, $altura) = getimagesize($imagem->getPathName());

        if($largura < "300" || $altura < "300"){

             $retorno['erro'] = 'Verifique os Dados';
             $retorno['erros_model'] = ['dimensao' => 'Imagem não pode ser menor que 300 x 300 pixels'];

             return $this->response->setJSON($retorno);
        }


        $imagemCaminho = $imagem->store('usuarios');

        //C:\xampp\htdocs\arctic-order\writable\uploads/usuarios/1683550992_020bfe063893f147eacd.jpg
        $imagemCaminho = WRITEPATH . "uploads/$imagemCaminho";

       //Manipular Imagem
       $this->manipulaImagem($imagemCaminho, $usuario->id);

        //Recuperar a Imagem Antiga        
        $imagemAntiga = $usuario->imagem;

        //A partir daqui ira atualizar a Tabela de Usuários
        $usuario->imagem = $imagem->getName();

        $this->usuarioModel->save($usuario);

        //Apagar Imagem Antiga
        if($imagemAntiga != null){
            $this->removeImagemDoFileSystem($imagemAntiga);
        }



        session()->setFlashdata('sucesso', 'Imagem Salva com Sucesso!');
        

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
    }


    //Função Carrega Imagem
    public function imagem(string $imagem = null)
    {

        if ($imagem != null) {
            $this->exibeArquivo('usuarios', $imagem);
        }
    }


    //Funcao Excluir
    public function excluir(int $id = null)
    {
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($id);

        if($usuario->data_exclusao != null){

            return redirect()->back()->with('info', "Esse Usuário já foi Excluído!");

        }

        if($this->request->getMethod() === 'post'){
            //Excluie o usuario
            $this->usuarioModel->delete($usuario->id);
            //Apagamos a Imagem
            if($usuario->imagem != null){

                $this->removeImagemDoFileSystem($usuario->imagem);                  
            }

            $usuario->imagem = null;
            $usuario->ativo = false;

            $this->usuarioModel->protect(false)->save($usuario);

            return redirect()->to(site_url("usuarios"))->with('sucesso', "Usuário $usuario->nome excluído com Sucesso!");

        }
        
        $data = [
            'titulo' => "Excluir Usuário ".esc($usuario->nome),
            'usuario' => $usuario,

        ];
        return view('Usuarios/excluir', $data);

    }


     //Funcao Restaurar Usuário
     public function restaurarUsuario(int $id = null)
     {
         //Validando o Usuário
         $usuario = $this->buscaUsuarioOu404($id);
 
         if($usuario->data_exclusao == null){
 
             return redirect()->back()->with('info', "Usuário não está Excluído!");
         }

         $usuario->data_exclusao = null;
         $this->usuarioModel->protect(false)->save($usuario);
 
         return redirect()->back()->with('sucesso', "Usuário $usuario->nome Recuperado com Sucesso!");
     }


     //Funcao Grupos e Usuarios
    public function grupos(int $id = null)
    {
        //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($id);

        $usuario->grupos = $this->grupoUsuarioModel->recuperaGruposDoUsuario($usuario->id, 5);
        $usuario->pager = $this->grupoUsuarioModel->pager;

        
        $data = [
            'titulo' => "Gerenciamento de Grupos de Acesso ".esc($usuario->nome),
            'usuario' => $usuario,

        ];

        // Quando o Usuario for cliente retornamos a View
        $grupoCliente = 2;
        if(in_array($grupoCliente, array_column($usuario->grupos, 'grupo_id'))){
            return redirect()->to(site_url("usuarios/exibir/$usuario->id"))
                            ->with('info', "Esse Usuário já faz parte do Grupo Clientes!");
        }

        $grupoAdmin = 1;
        if(in_array($grupoAdmin, array_column($usuario->grupos, 'grupo_id'))){
            $usuario->full_control = true; // Esta no Grupo admim, Ele já Retorna a view
            return view('Usuarios/grupos', $data);
        }

            $usuario->full_control = false; // Não esta no grupo admin, ele segue a seleção

        if(!empty($usuario->grupos)){

            //Recuperamos os Grupos que o usuario não faz parte
            $gruposExistentes = array_column($usuario->grupos, 'grupo_id');

            $data['gruposDisponiveis'] = $this->grupoModel
                                              ->where('id !=', 2)
                                              ->whereNotIn('id', $gruposExistentes)
                                              ->findAll();

        }else{

            //Recuperamos Todos os Grupos com exceção o ID 2 que é o cliente!
            $data['gruposDisponiveis'] = $this->grupoModel
                                              ->where('id !=', 2)
                                              ->findAll();
        }


        return view('Usuarios/grupos', $data);

    }


    //Metodo Salva Grupos
    public function salvarGrupos()
    {

         // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

         // Recupero o Post da requisição
        $post = $this->request->getPost();
 
   
         //Validando o Usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);

        if(empty($post['grupo_id'])){

            // Retorno de erro de Validação
            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['grupo_id' => 'Escolha uma ou mais Grupos!'];

            //Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }

        if(in_array(2, $post['grupo_id'])){

            // Retorno de erro de Validação
            $retorno['erro'] = 'Verifique os Dados';
            $retorno['erros_model'] = ['grupo_id' => 'O Grupo Clientes não pode Ser Atribuido Manualmente!'];

            //Retorno para o Ajax Request
            return $this->response->setJSON($retorno);
        }

        //Verificar se No Post está vindo um Grupo Admin
        if(in_array(1, $post['grupo_id'])){

            $grupoAdmin = [
                'grupo_id' => 1,
                'usuario_id' => $usuario->id,
            ];

            //Associar o Usuario apenas ao grupo Admin
            $this->grupoUsuarioModel->insert($grupoAdmin);
            //Remove Todos os Grupos que estavam associados
            $this->grupoUsuarioModel->where('grupo_id !=', 1)
                                    ->where('usuario_id', $usuario->id)
                                    ->delete();

            session()->setFlashdata('sucesso', 'Salvo com Sucesso!');
 
            return $this->response->setJSON($retorno);
        
        }

         //Recebe as Permissões do Post
        $grupoPush = [];

        foreach($post['grupo_id'] as $grupo){
             
             array_push($grupoPush, [
                 'grupo_id' => $grupo,
                 'usuario_id' => $usuario->id,
             ]);
        }

        $this->grupoUsuarioModel->insertBatch($grupoPush);
        session()->setFlashdata('sucesso', 'Salvo com Sucesso!');
 
        return $this->response->setJSON($retorno);
    }


    //Metodo Remove Grupos
    public function removeGrupo(int $principal_id = null)
    {

        if($this->request->getMethod() == 'post'){

            $grupoUsuario = $this->buscaGrupoUsuarioOu404($principal_id);

            if($grupoUsuario->grupo_id == 2){
                return redirect()->to(site_url("usuarios/exibir/$grupoUsuario->usuario_id"))->with("info", "Não é permitido a exclusão do Grupo Clientes!");
            }

            $this->grupoUsuarioModel->delete($principal_id);
            return redirect()->back()->with("sucesso", "Grupo Removido com Sucesso!");
        }


        //Não é POST
        return redirect()->back();
    }


    public function editarSenha()
    {

        $data = [
            'titulo' => 'Edite a senha de acesso',
        ];

        return view('Usuarios/editar_senha', $data);

    }


    public function atualizarSenha()
    {

        if (!$this->request->isAJAX()){ 
            return redirect()->back();
        }

        // Envio hash  Token do Form
        $retorno['token'] = csrf_hash();

        
        $current_password = $this->request->getPost('current_password');

        $usuario = usuario_logado();

        if($usuario->verificaPassword($current_password) === false){

            $retorno['erro'] = 'A Senha Digitada não é Válida!';
            $retorno['erros_model'] = ['current_password' => 'Digite a Senha Correta!'];
            return $this->response->setJSON($retorno);
        }

        $usuario->fill($this->request->getPost());

        if($usuario->hasChanged() === false){

            $retorno['info'] = 'Não a dados para serem Atualizados!';
            return $this->response->setJSON($retorno);
        }

      
        if($this->usuarioModel->save($usuario)){
            $retorno['sucesso'] = 'Senha Alterada com Sucesso!';

            return $this->response->setJSON($retorno);

        }

        // Retorno de erro de Validação
        $retorno['erro'] = 'Verifique os Dados';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        //Retorno para o Ajax Request
        return $this->response->setJSON($retorno);
        
        
    }



    /**
     * Método que Recupera o Usuário
     * 
     * @param integer $id
     * @return Exceptions|object
     */
    private function buscaUsuarioOu404(int $id = null)
    {

        if(! $id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuário não encontrado $id");
        }

        return $usuario;
    }


     //Metodo que Busca o Registro de Grupo e Permissões
     private function buscaGrupoUsuarioOu404(int $principal_id = null)
     {
 
         if(! $principal_id || !$grupoUsuario = $this->grupoUsuarioModel->find($principal_id)){
             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o Registro de Grupos $principal_id");
         }
 
         return $grupoUsuario;
     }


    //Metodo para Manipular a Imagem
    private function manipulaImagem(string $imagemCaminho, int $usuario_id)
    {

        //Manipular a Imagem que está no diretório
        service('image')
            ->withFile($imagemCaminho)
            ->fit(300, 300, 'center')
            ->save($imagemCaminho);

        
        $anoAtual = date('Y');
        // Adicionar Marca d'água na Imagem
        \Config\Services::image('imagick')
            ->withFile($imagemCaminho)
            ->text("Arctic $anoAtual - User-ID $usuario_id", [
                     'color'      => '#fff',
                     'opacity'    => 0.5,
                     'withShadow' => false,
                     'hAlign'     => 'center',
                     'vAlign'     => 'bottom',
                     'fontSize'   => 10,
        ])
        ->save($imagemCaminho);
    }


    //Metodo Remover Imagem
    private function removeImagemDoFileSystem(string $imagem)
    {

        $imagemCaminho = WRITEPATH . "uploads/usuarios/$imagem";

        if(is_file($imagemCaminho)){

            unlink($imagemCaminho);
        }

    }


}
