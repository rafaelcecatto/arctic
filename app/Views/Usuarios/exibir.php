<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
  <?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>
  
<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>
  
<div class="row">

    <div class="col-lg-4">

        <div class="user-block block">

            <div class="text-center">

                <?php if($usuario->imagem == null): ?>

                <img src="<?php echo site_url('recursos/img/usuario_sem_imagem.png'); ?>" class="card-img-top" style="width: 40%;" alt="Usuário sem Imagem">

                <?php else: ?>


                <img src="<?php echo site_url("usuarios/imagem/$usuario->imagem"); ?>" class="card-img-top" style="width: 40%;" alt="<?php echo esc($usuario->nome); ?>">

                <?php endif; ?>

                <a href="<?php echo site_url("usuarios/editarimagem/$usuario->id") ?>" class="btn btn-outline-primary btn-sm mt-3">Alterar Imagem</a>

            </div>

            <hr class="border-secondary">

            <h5 class="card-title mt-2"><?php echo esc($usuario->nome); ?></h5>
            <p class="card-text">E-mail:<?php echo esc($usuario->email); ?></p>
            <p class="contributions mt-0"><?php echo $usuario->exibeSituacao(); ?></p>
            <p class="card-text">Data de Cadastro: <?php echo $usuario->data_cadastro->humanize(); ?></p>
            <p class="card-text">Data de Alteração: <?php echo $usuario->data_alteracao->humanize(); ?></p>

                <!-- Example single danger button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Ações
                    </button>
                     <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo site_url("usuarios/editar/$usuario->id"); ?>">Editar</a>
                        <a class="dropdown-item" href="<?php echo site_url("usuarios/grupos/$usuario->id"); ?>">Grupos de Acesso</a>
                    <div class="dropdown-divider"></div>
                        <?php if($usuario->data_exclusao == null):  ?>
                            <a class="dropdown-item" href="<?php echo site_url("usuarios/excluir/$usuario->id"); ?>">Excluir</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?php echo site_url("usuarios/restaurarusuario/$usuario->id"); ?>">Recuperar</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo site_url("usuarios") ?>" class="btn btn-secondary btn-sm ml-2">Voltar</a>
            </div>
    </div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>
  
<?php echo $this->endSection() ?>