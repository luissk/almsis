<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
use App\Models\SalidaModel;
class Usuario extends Controller{

    public function __construct(){
        $this->modeloUsuario  = model('UsuarioModel');
        $this->modeloProducto = model('ProductoModel');
        $this->modeloEntrada  = model('EntradaModel');
        $this->modeloSalida   = model('SalidaModel');
        $this->modeloUsuario  = model('UsuarioModel');
        $this->session = \Config\Services::session();
    }

    public function index(){
        if(session('idtipousu') != 1){
            return redirect()->to('dashboard');
        }

        $data['title']        = 'Usuarios';
        $data['contenido']    = 'usuarios/index';
        $data['li_config']    = true;
        $data['act_usuarios'] = true;

        $data['usuarios'] = $this->modeloUsuario->listarUsuarios();

        return view('template/layout', $data);
    }

    public function updateStatusUsuario(){
        if(session('idtipousu') != 1){
            exit();
        }

        if( $this->request->isAJAX() ){
            $idusuario = $_POST['idusuario'];
            $status    = $_POST['status'];

            $estado = $status == 1 ? 0 : 1;

            $this->modeloUsuario->updateStatusUsuario($idusuario, $estado);
        }
    }

    public function nuevoUsuario($idusuario = null){
        if(session('idtipousu') != 1){
            return redirect()->to('dashboard');
        }

        $data['tiposUsuario'] = $this->modeloUsuario->tiposDeUsuario();
        $data['areas']        = $this->modeloSalida->getAreas();

        $data['title']        = 'Nuevo Usuario';
        $data['contenido']    = 'usuarios/nuevousuario';
        $data['li_config']    = true;
        $data['act_usuarios'] = true;

        if($idusuario != null){
            $usuario = $this->modeloUsuario->getUsuario($idusuario); // para editar
            $data['user'] = $usuario;
        }

        return view('template/layout', $data);
    }

    public function saveUpdateUsuario(){
        if(session('idtipousu') != 1){
            exit();
        }

        if( $this->request->isAJAX() ){
			$usuario   = trim($_POST['usuario']);
			$nombres   = trim($_POST['nombres']);
			$passw     = trim($_POST['password']);
			$dni       = trim($_POST['dni']);
			$idusuario = trim($_POST['idusuario']);

			$tusuario = isset($_POST['tusuario']) ? trim($_POST['tusuario']) : 1;
			$idarea   = isset($_POST['area']) ? $_POST['area'] : 'null';

            $password = crypt($passw, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

            $msg_err = "";
            if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $usuario)){
                $msg_err = "Usuario inválido";
            }else if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ,-_. ]+$/', $nombres)){
                $msg_err = "Nombre inválido";
            }else if(!preg_match('/^[0-9]+$/', $dni)){
                $msg_err = "DNI inválido";
            }

            if($msg_err == ""){

                if($idusuario != ''){
                    //update
                    $user = $this->modeloUsuario->getUsuario($idusuario);
                    if($user){
                        $usu_ant = $user['usuario'];
                        if($usu_ant != $usuario){
                            $existe = $this->modeloUsuario->existeUsuarioUsu($usuario);
                            if($existe['total'] > 0){
                                $msg_err = "El usuario ya existe";
                                echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
                                exit();
                            }
                        }
                        $password = $passw == '' ? $user['password'] : $password;
                        if($this->modeloUsuario->updateUsuario($usuario,$password,$nombres,$dni,$idusuario,$tusuario,$idarea)){
                            echo json_encode(array("err"=> $msg_err, "res" => "update"),JSON_UNESCAPED_UNICODE);
                        }
                    }                    
                }else{
                    //insert
                    $existe = $this->modeloUsuario->existeUsuarioUsu($usuario);
                    if($existe['total'] > 0){
                        $msg_err = "El usuario ya existe";
                        echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
                    }else{
                        $insert = $this->modeloUsuario->insertUsuario($usuario,$password,$nombres,$dni,$tusuario,$idarea);
                        
                        echo json_encode(array("err"=> $msg_err, "res" => "insert $insert"),JSON_UNESCAPED_UNICODE);
                    }              
                }
            }else{
                echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
            }
        }
    }


    public function backup(){
        if(session('idtipousu') != 1){
            return redirect()->to('dashboard');
        }

        $data['title']         = 'Backup BD';
        $data['contenido']     = 'usuarios/backup';
        $data['li_config']  = true;
        $data['act_backup'] = true;

        return view('template/layout', $data);
    }

    public function generarBackup(){
        if( $this->request->isAJAX() ){
            echo view('usuarios/backup_create');
        }
    }

    public function eliminarBackup($backup){
        if(session('idtipousu') != 1){
            return redirect()->to('dashboard');
        }

        unlink('public/backups/'.$backup);

        return redirect()->to('backup');
    }



    public function eliminarUsuario(){
        if( $this->request->isAJAX() ){
            if(session('idtipousu') != 1){
                exit();
            }

            $idusuario = $_POST['idusuario'];
            $user = $this->modeloUsuario->getUsuario($idusuario);

            if($user){
                $idtipousu = $user['idtipousu'];
                if($idtipousu == 1){
                    echo "<script>swal_alert('Atención', 'No puedes eliminar a un: admin', 'error', 'Aceptar')</script>";
                }else{
                    //verificar si tiene movimiento en tablas: entrada(idusuario), producto(idusuario), requerimiento(idusuario, idusuario2),
                    // salida(idusuario)
                    $tablas = [];
                    if( $this->modeloUsuario->vericaUsuEnProducto($idusuario) ){
                        $tablas[] = 'producto';
                    }
                    if( $this->modeloUsuario->vericaUsuEnEntrada($idusuario) ){
                        $tablas[] = 'entrada';
                    }
                    if( $this->modeloUsuario->vericaUsuEnSalida($idusuario) ){
                        $tablas[] = 'salida';
                    }
                    if( $this->modeloUsuario->vericaUsuEnRequerimiento($idusuario) ){
                        $tablas[] = 'requerimiento';
                    }

                    if(count($tablas) > 0){
                        //print_r($tablas);
                        //echo json_encode($tablas);
                        echo "<script>swal_alert('Atención', 'No puedes eliminarlo tiene movimiento en la tabla(s): ".json_encode($tablas)."', 'error', 'Aceptar')</script>";
                    }else{
                        if($this->modeloUsuario->eliminaUsuario($idusuario)){
                            echo "<script>swal_alert('Mensaje', 'Usuario eliminado.', 'success', 'Aceptar')</script>";
                            echo "<script>setTimeout(function(){location.reload()}, 2000)</script>";
                        }
                    }
                }
            }else{
                echo "<script>swal_alert('Atención', 'No existe el usuario', 'error', 'Aceptar')</script>";
            }

        }
    }

}