<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
  <?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>
  
<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>
  
<div class="row">

    <div class="col-lg-8">

        <div class="user-block block">


            <h5 class="card-title mt-2"><?php echo esc($fornecedor->razao); ?></h5>
            <p class="card-text">Nome Fantasia:&nbsp;<?php echo esc($fornecedor->fantasia); ?></p>
            <p class="card-text">CNPJ:&nbsp;<?php echo esc($fornecedor->cnpj); ?></p>
            <p class="contributions mt-0"><?php echo $fornecedor->exibeSituacao(); ?></p>
            <p class="card-text">Data de Cadastro: <?php echo $fornecedor->data_cadastro->humanize(); ?></p>
            <p class="card-text">Data de Alteração: <?php echo $fornecedor->data_alteracao->humanize(); ?></p>

                <!-- Example single danger button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Ações
                    </button>
                     <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo site_url("fornecedores/editar/$fornecedor->id"); ?>">Editar</a>
                        <a class="dropdown-item" href="<?php echo site_url("fornecedores/notas/$fornecedor->id"); ?>">Notas Fiscais</a>
                    <div class="dropdown-divider"></div>
                        <?php if($fornecedor->data_exclusao == null):  ?>
                            <a class="dropdown-item" href="<?php echo site_url("fornecedores/excluir/$fornecedor->id"); ?>">Excluir</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?php echo site_url("fornecedores/restaurarfornecedor/$fornecedor->id"); ?>">Recuperar</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo site_url("fornecedores") ?>" class="btn btn-secondary btn-sm ml-2">Voltar</a>
            </div>
    </div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>
  
<?php echo $this->endSection() ?>