<?php echo $this->extend('Layout/Autenticacao/principal_autenticacao'); ?>


<?php echo $this->section('titulo') ?>
<?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>

<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>

<div class="row">
  <!-- Logo & Information Panel-->
  <div class="col-lg-6">
    <div class="info d-flex align-items-center">
      <div class="content">
        <div class="logo">
          <h1>Arctic</h1>
        </div>
        <p>Sistema de Gestão</p>
      </div>
    </div>
  </div>
  <!-- Form Panel    -->
  <div class="col-lg-6 bg-white">
    <div class="form d-flex align-items-center">
      <div class="content">

        <h1><small>E-mail enviado com Sucesso</small></h1>

        <a href="<?php echo site_url('login'); ?>" class="forgot-pass mt-2">Voltar</a>

      </div>
    </div>
  </div>
</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>


<?php echo $this->endSection() ?>