<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
class Entrada extends Controller{

    public function __construct(){
        $this->modeloUsuario = model('UsuarioModel');
        $this->modeloProducto = model('ProductoModel');
        $this->modeloEntrada = model('EntradaModel');
        $this->session = \Config\Services::session();
    }

    public function index(){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']        = 'Entradas';
        $data['contenido']    = 'entradas/index';
        $data['li_entradas']  = true;
        $data['act_entradas'] = true;

        return view('template/layout', $data);
    }

    public function nuevaEntrada(){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']        = 'Nueva entrada';
        $data['contenido']    = 'entradas/nuevo';
        $data['li_entradas']  = true;
        $data['act_entradas'] = true;

        $producto = $this->modeloProducto->getProductos();
        $data['productos'] = $producto;
        return view('template/layout', $data);
    }

    public function saveEntrada(){
        if( $this->request->isAJAX() ){
			/* print_r($_POST);
            print_r(json_decode($_POST['items'], true)); */
            $fechareg   = $_POST['fechareg'];
            $documento  = $_POST['documento'];
            $comentario = $_POST['comentario'];
            $items      = json_decode($_POST['items'], true);

            $ok = FALSE;
            /*** 1. INSERTAR ENTRADA ***/
            $idusuario = session('idusuario');
            $identrada = $this->modeloEntrada->insertEntrada($fechareg, $documento, $comentario, $idusuario);

            if($identrada > 0){
                /*** 2. RECORRER ITEMS ***/
                foreach($items as $item){
                    $idproducto   = $item['idproducto'];
                    $cantidad     = $item['cantidad'];
                    /*** 3. BUSCAR PRODUCTO ***/
                    $producto = $this->modeloProducto->getProducto($idproducto);
                    if($producto){
                        /*** 4. INSERTAR DETALLE COMPRA ***/
                        if($this->modeloEntrada->insertDetalleEntrada($identrada,$idproducto,$cantidad)){
                            $ok = TRUE;
                        }
                    }
                }
                if($ok) echo 1;
            }
        }
    }

    public function listEntradasDT(){
        if( $this->request->isAJAX() ){
            $desc = trim($_POST['desc']);
			//echo json_encode($_POST,JSON_UNESCAPED_UNICODE);
			//print_r($_POST);
			$entrada = $this->modeloEntrada->getEntradas($desc);
			print json_encode($entrada, JSON_UNESCAPED_UNICODE);
        }
    }

    public function editEntrada($identrada){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }
        
        $entrada = $this->modeloEntrada->getEntrada($identrada);
        if($entrada){
            $detalle = $this->modeloEntrada->getDetalle($identrada);

            $data['title']        = 'Editar entrada';
            $data['contenido']    = 'entradas/edit';
            $data['li_entradas']  = true;
            $data['act_entradas'] = true;

            $producto = $this->modeloProducto->getProductos();
            $data['productos'] = $producto;
            
            $data['entrada'] = $entrada;
            $data['detalle'] = $detalle;
            return view('template/layout', $data);
        }else
            return redirect()->to( '/entradas' );
    }

    public function updateEntrada(){
        if( $this->request->isAJAX() ){
			/* print_r($_POST);
            print_r(json_decode($_POST['items'], true)); */
            $fechareg   = $_POST['fechareg'];
            $documento  = $_POST['documento'];
            $comentario = $_POST['comentario'];
            $identrada  = $_POST['identrada'];
            $items      = json_decode($_POST['items'], true);

            //SI NO EXISTE ENTRADA NO HACE PASA
            if(!$this->modeloEntrada->getEntrada($identrada)) exit();

            $ok = FALSE;
            /*** 1. UPDATE ENTRADA ***/
            $update = $this->modeloEntrada->updateEntrada($fechareg, $documento, $comentario, $identrada);
            if($update){
                /*** 2. ELIMINAR DETALLE_ENTRADA ***/
                $this->modeloEntrada->deleteDetalle($identrada);
                /*** 3. RECORRER ITEMS ***/
                foreach($items as $item){
                    $idproducto   = $item['idproducto'];
                    $cantidad     = $item['cantidad'];
                    /*** 4. BUSCAR PRODUCTO ***/
                    $producto = $this->modeloProducto->getProducto($idproducto);;
                    if($producto){
                        /*** 5. INSERTAR DETALLE ENTRADA ***/
                        if($this->modeloEntrada->insertDetalleEntrada($identrada,$idproducto,$cantidad)){
                            $ok = TRUE;
                        }
                    }
                }
                if($ok) echo 1;
            }
        }
    }

    public function deleteEntrada(){        
        if( $this->request->isAJAX() ){
            //print_r($_POST);
            $identrada = $_POST['identrada'];

            //SI NO EXISTE ENTRADA NO HACE PASA
            if(!$this->modeloEntrada->getEntrada($identrada)) exit();

            /*** 1. ELIMINAR DETALLE_ENTRADA ***/
            if( $this->modeloEntrada->deleteDetalle($identrada) ){
                /*** 2. ELIMINAR la ENTRADA ***/
                if( $this->modeloEntrada->deleteEntrada($identrada)  ){
                    echo "eliminado";
                }
            }
        }
    }

}