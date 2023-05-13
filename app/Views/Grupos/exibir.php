<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
  <?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>
  
<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>
  
<div class="row">

    
    <?php if($grupo->id < 3):  ?>
        <div class="col-lg-12">
            <div class="alert alert-info" role="alert">
                 O grupo <b><?php echo esc($grupo->nome)?></b> não pode ser excluído!
            </div>
        </div>    
    <?php endif; ?>
    
    

    <div class="col-md-12">

    
        <div class="user-block block">

            
            <h5 class="card-title mt-2"><?php echo esc($grupo->nome); ?></h5>
            <p class="card-text"><?php echo esc($grupo->descricao); ?></p>

            <p class="contributions mt-0"><?php echo $grupo->exibeSituacao(); ?>
        
            <?php if($grupo->data_exclusao == null): ?>
                <a tabindex="0" style="text-decoration: none;" role="button" data-toggle="popover" data-trigger="focus" title="<b class='text-danger'>Importante</b>" data-content="Esse Grupo <?php echo($grupo->exibir == true ? 'será' : 'não será'); ?> exibido como opção na hora de definir um <b>Responsável Técnico Pela Ordem de Serviço!</b>">&nbsp;<i class="fa fa-question-circle fa-lg text-danger"></i></a>
            <?php endif; ?>
            </p>

            <p class="card-text">Data de Cadastro: <?php echo $grupo->data_cadastro->humanize(); ?></p>
            <p class="card-text">Data de Alteração: <?php echo $grupo->data_alteracao->humanize(); ?></p>

                <!-- Example single danger button -->
                <?php if($grupo->id > 2):  ?>

                    <div class="btn-group mr-2">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Ações
                    </button>
                     <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo site_url("grupos/editar/$grupo->id"); ?>">Editar</a>

                        <?php if($grupo->id > 2): ?>
                            <a class="dropdown-item" href="<?php echo site_url("grupos/permissoes/$grupo->id"); ?>">Alterar Permissões</a>
                        <?php endif; ?>

                    <div class="dropdown-divider"></div>
                        <?php if($grupo->data_exclusao == null):  ?>
                            <a class="dropdown-item" href="<?php echo site_url("grupos/excluir/$grupo->id"); ?>">Excluir</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?php echo site_url("grupos/restaurargrupo/$grupo->id"); ?>">Recuperar</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php endif; ?>

                <a href="<?php echo site_url("grupos") ?>" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>
  
<?php echo $this->endSection() ?>