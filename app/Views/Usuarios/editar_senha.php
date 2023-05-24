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

            <!--Exibe o Retorno do Backend -->
            <div id="response">

            </div>
            <!--Abre Formulário-->
            <?php echo form_open('/', ['id' => 'form']) ?>

            <div class="form-group">
                <label class="form-control-label">Senha Atual</label>
                <input type="password" name="current_password" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-control-label">Nova Senha</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-control-label">Confirme a Nova Senha</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="form-group mt-5 mb-2">

                <input id="btn-salvar" type="submit" value="Salvar" class="btn btn-info btn-sm mr-2">

                <a href="<?php echo site_url("usuarios") ?>" class="btn btn-secondary btn-sm ml-2">Voltar</a>
            </div>

            <!--Fecha Forma-->
            <?php echo form_close(); ?>

        </div>


    </div>

</div>

</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>

<!--Ajax de Edição -->
<script>
    $(document).ready(function(){

        $("#form").on('submit', function(e){

            e.preventDefault();

            $.ajax({

                type: 'POST',
                url: '<?php echo site_url('usuarios/atualizarsenha'); ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(){

                    $("#response").html('');
                    $("#btn-salvar").val('Aguarde');

                },
                success: function(response){

                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");

                    $('[name=csrf_test_name]').val(response.token); 

                    if(!response.erro){

                        $("#form")[0].reset();

                        if(response.info){

                            $("#response").html('<div class="alert alert-info">' + response.info + '</div>');

                        } else{

                            $("#response").html('<div class="alert alert-success">' + response.sucesso + '</div>');
                        }
                    }

                    if(response.erro){

                        // Erros de Validação
                        $("#response").html('<div class="alert alert-danger">' + response.erro + '</div>');

                        if(response.erros_model){

                            $.each(response.erros_model, function(key, value){

                              $("#response").append('<ul class="list-unstyled"><li class="text-danger">'+ value +'</li<</ul>');  

                            });
                        }
                    }
                },
                error: function(){
                    alert('Não foi Possivel Salvar!');
                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr("disabled");
                }
            });
        });


        //Desabilitar o duplo Click do salvar
        $("#form").submit(function() {

            $(this).find(":submit").attr('disabled', 'disabled');

        });
        
    });
</script>

<?php echo $this->endSection() ?>