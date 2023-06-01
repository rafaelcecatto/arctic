$('[name=cep]').on('keyup', function() {

var cep = $(this).val();

if (cep.length === 9) {

    $.ajax({

        type: 'GET',
        url: '<?php echo site_url('fornecedores/consultacep'); ?>',
        data: {
            cep: cep
        },
        dataType: 'json',
        beforeSend: function() {

            $("#form").LoadingOverlay("show");

            $("#cep").html('');
            

        },
        success: function(response) {

            $("#form").LoadingOverlay("hide", true);

            if (!response.erro) {

                if(!response.endereco){

                    $('[name=endereco]').prop('readonly', false);
                    $('[name=endereco]').focus();
                }

                if(!response.bairro){

                    $('[name=bairro]').prop('readonly', false);
                }


                $('[name=endereco]').val(response.endereco);
                $('[name=cidade]').val(response.cidade);
                $('[name=bairro]').val(response.bairro);
                $('[name=uf]').val(response.uf);

                
            }

            if (response.erro) {

                $("#cep").html(response.erro);
               
            }
        },
        error: function() {
            $("#form").LoadingOverlay("hide", true);
            
            alert('Não foi Possivel Salvar!');
           
        }
    });
}
});