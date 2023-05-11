<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
<?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>

<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>

<div class="row">

    <div class="col-lg-6">

        <div class="block">

           <div class="block-body">

          
            <!--Abre Formulário-->
            <?php echo form_open("usuarios/excluir/$usuario->id") ?>

            <div class="alert alert-warning" role="alert">
                Deseja Realmente Excluir o Usuário?
            </div>

            <div class="form-group mt-5 mb-2">

                <input id="btn-salvar" type="submit" value="SIM" class="btn btn-primary btn-sm mr-2">
                <a href="<?php echo site_url("usuarios/exibir/$usuario->id") ?>" class="btn btn-secondary btn-sm ml-2">Cancelar</a>
            </div>

            <!--Fecha Forma-->
            <?php echo form_close(); ?>

        </div>


    </div>

</div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>


<?php echo $this->endSection() ?>