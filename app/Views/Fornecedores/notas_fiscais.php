<?php echo $this->extend('Layout/principal') ?>


<?php echo $this->section('titulo') ?>
<?php echo $titulo; ?>
<?php echo $this->endSection() ?>


<?php echo $this->section('estilos') ?>

<?php echo $this->endSection() ?>


<?php echo $this->section('conteudo') ?>

<div class="row">

    <small><div class="col-lg-12">

        <div class="block">

            <div class="block-body">

                <!--Exibe o Retorno do Backend -->
                <div id="response">

                </div>
                <!--Abre Formulário-->
                <?php echo form_open_multipart('/', ['id' => 'form'], ['id' => "$fornecedor->id"]) ?>

                <div class="form-group">
                    <label class="form-control-label">Valor Nota Fiscal</label>
                    <input type="text" name="valor_nota" placeholder="Insira o Valor" class="form-control money">
                </div>

                <div class="form-group">
                    <label class="form-control-label">Data de Emissão</label>
                    <input type="date" name="data_emissao" placeholder="Data Emissão" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-control-label">PDF da Nota</label>
                    <input type="file" name="nota_fiscal" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-control-label">Observações da Nota</label>
                    <textarea name="descricao_itens" placeholder="Observações da Nota Fiscal" class="form-control"></textarea>
                </div>

                <div class="form-group mt-5 mb-2">

                    <input id="btn-salvar" type="submit" value="Salvar" class="btn btn-info btn-sm mr-2">
                    <a href="<?php echo site_url("fornecedores/exibir/$fornecedor->id") ?>" class="btn btn-secondary btn-sm ml-2">Voltar</a>
                </div>

                <!--Fecha Forma-->
                <?php echo form_close(); ?>

            </div>


        </div>

    </div></small>


    <div class="col-lg-8">


        <div class="user-block block">

            <?php if (empty($fornecedor->notas_fiscais)) : ?>

                <p class="contributions text-warning mt-0">Esse Fornecedor Não Possui Notas Fiscais!</p>

            <?php else : ?>

                <div class="table-responsive">

                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Data de Emissão</th>
                                <th>Valor</th>
                                <th>Observações</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($fornecedor->notas_fiscais as $nota) : ?>
                                <tr>
                                    <td><?php echo esc($nota->data_emissao); ?></td>
                                    <td><?php echo esc($nota->valor_nota); ?></td>
                                    <td><?php echo ellipsize($nota->descricao_itens, 20, .5); ?></td>
                                    <td>

                                        <?php 
                                            $atributos = [
                                                'onSubmit' => "return confirm('Deseja realmente Excluir?');",
                                            ]; 
                                        ?>
                                        <!--Abre Formulário-->
                                        <?php echo form_open("fornecedores/removenota/$nota->id", $atributos) ?>
                            
                                        <button type="submit" href="#" class="btn btn-sm btn-danger">Excluir</button>

                                        <?php echo form_close(); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>

                    <div class="mt-3 ml-1">
                        <?php echo $fornecedor->pager->links(); ?>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>

<script src="<?php echo site_url('recursos/vendor/loadingoverlay/loadingoverlay.min.js') ?>"></script>

<script src="<?php echo site_url('recursos/vendor/mask/jquery.mask.min.js') ?>"></script>
<script src="<?php echo site_url('recursos/vendor/mask/app.js') ?>"></script>

<!--Ajax de Edição -->
<script>
    $(document).ready(function() {

        
        $("#form").on('submit', function(e) {

            e.preventDefault();

            $.ajax({

                type: 'POST',
                url: '<?php echo site_url('fornecedores/cadastrarnota'); ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $("#response").html('');
                    $("#btn-salvar").val('Aguarde');

                },
                success: function(response) {

                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");

                    $('[name=csrf_test_name]').val(response.token);

                    if (!response.erro) {

                        window.location.href = "<?php echo site_url("fornecedores/notas/$fornecedor->id"); ?>";
                        
                    }

                    if (response.erro) {

                        $("#response").html('<div class="alert alert-danger">' + response.erro + '</div>');

                        if (response.erros_model) {

                            $.each(response.erros_model, function(key, value) {

                                $("#response").append('<ul class="list-unstyled"><li class="text-danger">' + value + '</li<</ul>');

                            });
                        }
                    }
                },
                error: function() {
                    alert('Não foi Possivel Salvar!');
                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");
                }
            });
        });


        $("#form").submit(function() {

            $(this).find(":submit").attr('disabled', 'disabled');

        });

    });
</script>

<?php echo $this->endSection() ?>