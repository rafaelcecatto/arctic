<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Arctic - Sistema de Gestão | <?php echo $titulo ?> </title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>vendor/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome CSS-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>vendor/font-awesome/css/font-awesome.min.css">
  <!-- Custom Font Icons CSS-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>css/font.css">
  <!-- Google fonts - Muli-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>https://fonts.googleapis.com/css?family=Muli:300,400,700">
  <!-- theme stylesheet-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>css/style.default.css" id="theme-stylesheet">
  <!-- Custom stylesheet - for your changes-->
  <link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>css/custom.css">
  <!-- Favicon-->
  <link rel="shortcut icon" href="<?php echo site_url('recursos/'); ?>img/favicon.ico">
  <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

</head>

<body>
  <div class="login-page">
    <div class="container d-flex align-items-center">
      <div class="form-holder has-shadow">
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

                <?php echo form_open('/', ['id' => 'form', 'class' => 'form-validate']) ?>

                <div id="response">

                </div>

                <?php echo $this->include('Layout/_msgs'); ?>

                <div class="form-group">
                  <input id="login-username" type="text" name="email" required data-msg="Por Favor informe seu e-mail" class="input-material">
                  <label for="login-username" class="label-material">E-mail</label>
                </div>
                <div class="form-group">
                  <input id="login-password" type="password" name="password" required data-msg="Por favor informe sua senha" class="input-material">
                  <label for="login-password" class="label-material">Senha</label>
                </div>
                <input id="btn-login" type="submit" class="btn btn-primary" value="Entrar">
                <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                <?php echo form_close(); ?>

                <a href="#" class="forgot-pass mt-2">Esqueceu a Senha?</a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyrights text-center">
      <p><?php echo date('Y') ?> &copy; Arctic | Sistema de Gestão <a target="_blank" href="https://sevenautomacao.net.br">Seven Automação</a>.</p>
    </div>
  </div>
  <!-- JavaScript files-->

  <!-- JavaScript files-->
  <script src="<?php echo site_url('recursos/'); ?>vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo site_url('recursos/'); ?>vendor/popper.js/umd/popper.min.js"> </script>
  <script src="<?php echo site_url('recursos/'); ?>vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo site_url('recursos/'); ?>vendor/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo site_url('recursos/'); ?>js/front.js"></script>



  <!--Ajax de Login -->
  <script>
    $(document).ready(function() {

      $("#form").on('submit', function(e) {

        e.preventDefault();

        $.ajax({

          type: 'POST',
          url: '<?php echo site_url('login/criar'); ?>',
          data: new FormData(this),
          dataType: 'json',
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() {

            $("#response").html('');
            $("#btn-login").val('Aguarde');

          },
          success: function(response) {

            $("#btn-login").val('Entrar');
            $("#btn-login").removeAttr("disabled");

            $('[name=csrf_test_name]').val(response.token);

            if (!response.erro) {

              // Redirecionamento, quando não houver erro
              window.location.href = "<?php echo site_url(); ?>" + response.redirect;

            }

            if (response.erro) {

              // Erros de Validação
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
            $("#btn-login").val('Entrar');
            $("#btn-login").removeAttr("disabled");
          }
        });
      });


      //Desabilitar o duplo Click do salvar
      $("#form").submit(function() {

        $(this).find(":submit").attr('disabled', 'disabled');

      });

    });
  </script>


</body>

</html>