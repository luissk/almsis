<?php
/* if(isset($_SESSION['idusuario']) && $_SESSION['idusuario'] != ''){
    header('location:'.BASE_URL.'/admin/inicio');
} */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso ALSIS</title>

    <!-- <link rel="apple-touch-icon" sizes="180x180" href="<?php //echo BASE_URL?>/public/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php //echo BASE_URL?>/public/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php //echo BASE_URL?>/public/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php //echo BASE_URL?>/public/img/favicon/manifest.json"> -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">

                <div class="text-center my-3">
                    <h3><strong>ALMSIS<strong></h3>
                </div>
                
                <form action="" method="post" id="frmLogin">
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Usuario" name="usuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    </div>
                    <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Ingresa ahora</button>
                    </div>
                    <!-- /.col -->
                    </div>
                </form>

                <div class="text-center my-3">
                    <div id="msjlogin"></div>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- jQuery -->
    <script src="<?=base_url('public/adminlte')?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=base_url('public/adminlte')?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?=base_url('public/adminlte')?>/dist/js/adminlte.min.js"></script>


    <script>
    $(function(){
        $("#frmLogin").on('submit', function(e){
            e.preventDefault();
            let btn = $("#frmLogin button"),
                text = btn.text();
                btn.attr('disabled','disabled');
            $("#msjlogin").html("Validando...");
            $.ajax({
                method: 'POST',
                url: 'inicio',
                data: $(this).serialize(),
                success: function(data){
                    $("#msjlogin").html(data);
                    btn.removeAttr('disabled');
                    btn.text(text);
                }
            });
        })
    })
    </script>

</body>
</html>