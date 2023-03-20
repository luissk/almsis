<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
use App\Models\SalidaModel;

class Inicio extends Controller{

    protected $helpers = ['funciones'];//helpers

    public function __construct(){
        $this->modeloUsuario  = model('UsuarioModel');
        $this->modeloProducto = model('ProductoModel');
        $this->modeloEntrada  = model('EntradaModel');
        $this->modeloSalida   = model('SalidaModel');

        $this->session = \Config\Services::session();
    }


    public function index(){
        if($this->session->has('idusuario')){
            //echo $this->session->get('usuario');
            return redirect()->to('dashboard');
        }

        if( $this->request->isAJAX() ){
            //echo crypt('admin123', '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

            $usuario  = strtoupper(trim($this->request->getVar('usuario')));
            $password = trim($this->request->getVar('password'));

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $usuario)){
                $passcrypt = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $result = $this->modeloUsuario->validaUsuario($usuario);
                if( $result && strtolower($result['usuario']) == strtolower($usuario) && $result['password'] == $passcrypt ){

                    /* if($result['status'] == 0){
                        echo "EL USUARIO SE ENCUENTRA INACTIVO";
                        exit();
                    } */

                    $idusuario = $result['idusuario'];
                    $usuario   = $result['usuario'];
                    $idtipousu = $result['idtipousu'];

                    $user = $this->modeloUsuario->getUsuario($idusuario);

                    $datasession = [
                        'idusuario' => $idusuario,
                        'usuario' => $usuario,
                        'idtipousu' => $idtipousu,
                        'tipousu' => $user['tipo']
                    ];
                    $this->session->set($datasession);

                    echo "<script>location.reload()</script>";
                }else{
                    echo "PASSWORD y/o USUARIO INVALIDOS";
                }
            }else{
                echo "* USUARIO INVÁLIDO!";
            }
        }else{
            return view('index');
        }
    }

    public function dashboard(){
        $data['title']         = 'Dashboard';
        $data['contenido']     = 'inicio';
        $data['act_dashboard'] = true;
        return view('template/layout', $data);
    }

    public function salir(){
        $this->session->destroy();
        return redirect()->to('/');
    }

    //DASHBOARD
    public function counters_dashboard(){
        if( $this->request->isAJAX() ){
            $productos = $this->modeloProducto->getProductos();
            $count_pro = count($productos);
            $entradas  = $this->modeloEntrada->getEntradas();
            $count_ent = count($entradas);
            $salidas   = $this->modeloSalida->getSalidas();
            $count_sal = count($salidas);

            $arr = [$count_pro,$count_ent,$count_sal];

            echo json_encode($arr);
        }
    }

    public function grafica_salidas(){
        if( $this->request->isAJAX() ){
            $data_graf = $this->modeloSalida->grafica_salidas();
            //header("HTTP/1.1 200 OK");
            echo json_encode($data_graf);
        }
        
    }

    public function topProductosMov(){
        if( $this->request->isAJAX() ){
            $data_graf = $this->modeloSalida->topProductosMov();
            //header("HTTP/1.1 200 OK");
            echo json_encode($data_graf);
        }
    }

}