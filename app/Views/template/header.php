<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

  <!--SweetAlert2-->
  <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/sweetalert2/sweetalert2.min.css">
    <!--select2-->
    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="<?=base_url('public')?>/js/jquery-3.5.1.js"></script>

    <style>
      body{font-size:14px;}
      .table td, .table th{padding: .4rem .75rem}
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <?php
      //NOTIFICACIONES
      if(session('idtipousu') == 1 || session('idtipousu') == 2){
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge reqCount"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <a href="requerimiento" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i> <span class='reqCount'></span> requerimiento(s)
        </a>
      </li>
      <?php
      }
      ?>

     <!--  <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="chat" class="dropdown-item dropdown-footer">Ver chat</a>
        </div>
      </li> -->

      <li class="nav-item dropdown">
        <a href="<?php echo base_url('inicio/salir')?>" class="nav-link">
          <i class="fas fa-power-off"></i> <b>SALIR</b>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->


<?php
//NOTIFICACIONES
if(session('idtipousu') == 1 || session('idtipousu') == 2){
?>
<script>
  $(function(){
    //notificacion
    function notificacionReq(){
      $.post('requerimiento/notificacionReq',{}, function(data){
        if(data > 0){
          $(".reqCount").text(data);
        }else{
          $(".reqCount").text("");
        }
      })
    }

    setInterval(() => {
      notificacionReq();
    }, 10000);

    notificacionReq();
  })
</script>
<?php
}
?>