<?php
//echo session('idusuario');
/****SI NO EXISTE LA SESSION, DEBE LOGGEARSE ****/
if(!isset($_SESSION['idusuario'])){
  header('location: '.base_url().'');
  exit();
}
/**************/

/**** SI YA NO ESTA ACTIVO, DEBE SALIR DE LA SESSION ****/
$modeloUsuario = model('UsuarioModel');
$usu_bd = $modeloUsuario->getUsuario(session('idusuario'));
if($usu_bd['status'] == 0){
  header('location: '.base_url().'/inicio/salir');
  exit();
}
/**************/

echo view('template/header');
echo view('template/menu');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php echo view($contenido);?>
  <!-- /.content -->
  </div>
<?php
echo view('template/footer');
?>
  

