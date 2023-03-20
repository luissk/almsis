<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
/* use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
use App\Models\SalidaModel;
use App\Models\RequerimientoModel; */
use App\Models\ChatModel;
class Chat extends Controller{

    protected $helpers = ['funciones'];

    public function __construct(){
        /* $this->modeloUsuario       = model('UsuarioModel');
        $this->modeloProducto      = model('ProductoModel');
        $this->modeloEntrada       = model('EntradaModel');
        $this->modeloSalida        = model('SalidaModel');
        $this->modeloRequerimiento = model('RequerimientoModel'); */

        $this->modeloChat = model('ChatModel');
        $this->session = \Config\Services::session();
    }

    public function index(){
        if(!session('idusuario')) return redirect()->to('dashboard');

        $idusuario = session('idusuario');
        $usuarios  = $this->modeloChat->listarUsuarios($idusuario);

        $data['usuarios'] = $usuarios;

        $data['title'] = 'Chat';
        $data['contenido'] = 'chat/index';

        return view('template/layout', $data);
    }

    public function loadChat(){
        if(!session('idusuario')) exit();

        if( $this->request->isAJAX() ){
            $emisor   = session('idusuario');
            $receptor = $_POST['idusuario'];

            $chat = $this->modeloChat->cargarChat($emisor, $receptor);

            if($chat){

                //LEER CHAT
                foreach($chat as $ch){
                    $idchat = $ch['idchat'];
                    $rec    = $ch['receptor'];
                    $leido  = $ch['leido'];

                    if($emisor == $rec && $leido == 0){
                        $this->modeloChat->actualizaChatLeido($idchat);
                    }
                }                
                //FIN LEER CHAR


                $data['chat']     = $chat;
                $data['emisor']   = $emisor;
                $data['receptor'] = $receptor;
                //print_r($chat);
                return view('chat/cargachat', $data);
            }            
        }
    }

    public function insertMessage(){
        if(!session('idusuario')) exit();

        if( $this->request->isAJAX() ){
            $mensaje  = trim($_POST['message']);
            $receptor = $_POST['idusuario'];
            $emisor   = session('idusuario');

            if( $receptor == '' ){
                echo "<script>swal_alert('Atención', 'Selecciona un usuario', 'warning', 'Aceptar')</script>";
            }else if( $mensaje == '' ){
                echo "<script>swal_alert('Atención', 'Escribe un mensaje', 'warning', 'Aceptar')</script>";
            }else{
                if($this->modeloChat->insertMessage($emisor, $receptor, $mensaje)){
                    echo "<script>loadChat($receptor);</script>";
                }
            }
        }
    }

    public function deleteChat(){
        if(!session('idusuario')) exit();

        if( $this->request->isAJAX() ){
            $receptor = $_POST['idusuario'];

            if( $receptor == '' ){
                echo "<script>swal_alert('Atención', 'Selecciona un usuario, para borrar el Chat', 'warning', 'Aceptar')</script>";
            }else{
                $emisor   = session('idusuario');

                $chat = $this->modeloChat->cargarChat($emisor, $receptor);

                if($chat){
                    if( $this->modeloChat->borrarChat($emisor, $receptor) ){
                        echo "<script>swal_alert('Mensaje', 'Se eliminaron los mensajes', 'success', 'Aceptar')</script>";
                        echo "<script>loadChat($receptor)</script>";
                    }
                }else{
                    echo "<script>swal_alert('Atención', 'No existe el Chat', 'error', 'Aceptar')</script>";
                }
            }
        }
    }

    public function newMessages(){
        if(!session('idusuario')) exit();

        $emisor   = session('idusuario');

        if( $this->request->isAJAX() ){
            $ids = $_POST['ids'];
            if(count($ids) > 0){
                foreach($ids as $id){
                    $chat = $this->modeloChat->cargarChat($emisor, $id);
                    $count_noleido = '0';
                    foreach($chat as $ch){
                        $idchat   = $ch['idchat'];
                        $receptor = $ch['receptor'];
                        $leido    = $ch['leido'];

                        if($emisor == $receptor && $leido == 0){
                            $count_noleido++;
                        }
                    }
                    if($count_noleido > 0){
                        echo "<script>$('#msjnuevos-".$id."').show()</script>";
                    }else{
                        echo "<script>$('#msjnuevos-".$id."').hide()</script>";
                    }
                    //echo "$count_noleido - $id<br>";
                }
            }
        }
    }

}