<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
  <?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>

<link href="<?php echo site_url('recursos/vendor/datatable/datatables.min.css') ?>" rel="stylesheet"/>
 

  
<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>

<div class="row">

<!--DataTable -->
<div class="col-lg-12">
                <div class="block">

                <a href="<?php echo site_url('fornecedores/criar'); ?>" class="btn btn-info btn-sm mb-4">Novo</a>
                  <div class="title"><strong>Fornecedor</strong></div>
                  <div class="table-responsive"> 
                    <table id="ajaxTable" class="table table-striped table-sm" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Razão Social</th>
                          <th>Nome Fantasia</th>
                          <th>CNPJ</th>
                          <th>Telefone</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
</div>
  
<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>

<script type="text/javascript" src="<?php echo site_url('recursos/vendor/datatable/datatables.min.js') ?>"></script>

<script>

$(document).ready(function () {

  const DATATABLE_PTBR = {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }

    $('#ajaxTable').DataTable({
        "oLanguage": DATATABLE_PTBR,
        ajax: "<?php echo site_url('fornecedores/recuperafornecedores'); ?>",
        columns: [
            { data: "razao" },
            { data: "fantasia" },
            { data: "cnpj" },
            { data: "telefone" },
            { data: "ativo" },
        ],
        "deferRender": true,
        "processing": true,
        "responsive": true,
        "pagingType": $(window).width() < 768 ? "simple" : "simple_numbers",
    });
});

</script>
  
<?php echo $this->endSection() ?>