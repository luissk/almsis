<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
use App\Models\SalidaModel;
class Producto extends Controller{

    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario  = model('UsuarioModel');
        $this->modeloProducto = model('ProductoModel');
        $this->modeloEntrada  = model('EntradaModel');
        $this->modeloSalida   = model('SalidaModel');
        $this->session = \Config\Services::session();
    }

    public function categorias(){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']          = 'Categorías';
        $data['contenido']      = 'producto/categorias';
        $data['li_productos']   = true;
        $data['act_categorias'] = true;

        $data['categorias'] = $this->modeloProducto->getCategorias();
        return view('template/layout', $data);
    }

    public function saveUpdateCategoria(){
        if(session('idtipousu') == 3){
            exit();
        }

        //print_r($_POST);
        if( $this->request->isAJAX() ){
            $categoria   = trim($_POST['categoria']);
            $idcategoria = trim($_POST['idcategoria']);

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $categoria)){

                if( $idcategoria != '' ){
                    //update
                    $result = $this->modeloProducto->updateCategoria($categoria, $idcategoria);
                    if($result){
                        echo 'update';
                    }else{
                        echo 'err';
                    }
                }else{
                    //insert
                    $result = $this->modeloProducto->saveCategoria($categoria);
                    if($result > 0){
                        echo 'insert';
                    }else{
                        echo 'err';
                    }
                }

            }
        }
    }

    public function eliminarCategoria(){
        if(session('idtipousu') == 3){
            exit();
        }

        if( $this->request->isAJAX() ){
            $idcategoria = $_POST['idcategoria'];
            $fila = $this->modeloProducto->existsEnProducto($idcategoria);
            if( $fila['total'] > 0 ){
                echo $fila['total'];
            }else{
                $result = $this->modeloProducto->deleteCategoria($idcategoria);
                if($result){
                    echo "delete";
                }else{
                    echo "error";
                }
            }
        }
    }

    public function umedidas(){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']          = 'Unidad de medidas';
        $data['contenido']      = 'producto/umedidas';
        $data['li_productos']   = true;
        $data['act_umedidas']   = true;

        $data['medidas'] = $this->modeloProducto->getMedidas();
        return view('template/layout', $data);
    }

    public function saveUpdateMedida(){
        if(session('idtipousu') == 3){
            exit();
        }

        //print_r($_POST);
        if( $this->request->isAJAX() ){
            $medida   = trim($_POST['medida']);
            $idum = trim($_POST['idum']);

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $medida)){

                if( $idum != '' ){
                    //update
                    $result = $this->modeloProducto->updateMedida($medida, $idum);
                    if($result){
                        echo 'update';
                    }else{
                        echo 'err';
                    }
                }else{
                    //insert
                    $result = $this->modeloProducto->saveMedida($medida);
                    if($result > 0){
                        echo 'insert';
                    }else{
                        echo 'err';
                    }
                }

            }
        }
    }

    public function eliminarMedida(){
        if(session('idtipousu') == 3){
            exit();
        }

        if( $this->request->isAJAX() ){
            $idum = $_POST['idum'];
            $fila = $this->modeloProducto->existsUMEnProducto($idum);
            if( $fila['total'] > 0 ){
                echo $fila['total'];
            }else{
                $result = $this->modeloProducto->deleteMedida($idum);
                if($result){
                    echo "delete";
                }else{
                    echo "error";
                }
            }
        }
    }

    //MANTTO PRODUCTOS
    public function productos(){
        $data['title']         = 'Productos';
        $data['contenido']     = 'producto/productos';
        $data['li_productos']  = true;
        $data['act_productos'] = true;

        //$data['productos'] = $this->modeloProducto->getProductos();
        return view('template/layout', $data);
    }

    public function listProductosDT(){
        if( $this->request->isAJAX() ){
            $desc = trim($_POST['desc']);
			//echo json_encode($_POST,JSON_UNESCAPED_UNICODE);
			//print_r($_POST);
			$producto = $this->modeloProducto->getProductos($desc);
			print json_encode($producto, JSON_UNESCAPED_UNICODE);
        }
    }

    public function nuevoProducto($idproducto = null){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']         = 'Nuevo Producto';
        $data['contenido']     = 'producto/nuevoproducto';
        $data['li_productos']  = true;
        $data['act_productos'] = true;

        if($idproducto != null){
            $producto = $this->modeloProducto->getProducto($idproducto); // para editar
            $data['producto'] = $producto;
        }

        $data['categorias'] = $this->modeloProducto->getCategorias();
        $data['medidas']    = $this->modeloProducto->getMedidas();
        return view('template/layout', $data);
    }

    public function saveUpdateProducto(){
        if(session('idtipousu') == 3){
            exit();
        }

        if( $this->request->isAJAX() ){
			$codigo      = trim($_POST['codigo']);
			$nombre      = trim($_POST['nombre']);
			$categoria   = trim($_POST['categoria']);
			$medida      = trim($_POST['medida']);
			$stock       = trim($_POST['stock']);
			$min         = trim($_POST['min']);
			$max         = trim($_POST['max']);
			$ubicacion   = trim($_POST['ubicacion']);
			$descripcion = trim($_POST['descripcion']);

            $idproducto       = trim($_POST['idproducto']);

            $msg_err = "";
            if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $codigo)){
                $msg_err = "Código inválido";
            }else if(empty($nombre)){
                $msg_err = "Nombre inválido";
            }else if(!preg_match('/^[0-9]+$/', $stock)){
                $msg_err = "Stock inválido";
            }else if(!preg_match('/^[0-9]+$/', $min)){
                $msg_err = "Mínimo inválido";
            }else if(!preg_match('/^[0-9]+$/', $max)){
                $msg_err = "Máximo inválido";
            }else if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ,-_. ]+$/', $ubicacion)){
                $msg_err = "Ubicación inválida";
            }else if($descripcion == ''){
                $msg_err = "Descripción inválida";
            }

            if($msg_err == ""){
                if($idproducto != ''){
                    //update
                    $producto = $this->modeloProducto->getProducto($idproducto);
                    if($producto){
                        $cod_ant = $producto['codigo'];
                        if($cod_ant != $codigo){
                            $existe = $this->modeloProducto->existeCodigoPro($codigo);
                            if($existe['total'] > 0){
                                $msg_err = "El código ya existe";
                                echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
                                exit();
                            }
                        }
                        if($this->modeloProducto->updateProducto($codigo,$nombre,$categoria,$medida,$stock,$min,$max,$ubicacion,$descripcion,$idproducto)){
                            echo json_encode(array("err"=> $msg_err, "res" => "update"),JSON_UNESCAPED_UNICODE);
                            
                            //GUARDAR MOVIMIENTO UPDATE
                            date_default_timezone_set('America/Lima');
                            $fecha = date("Y-m-d H:i:s");
                            $idusuario = session('idusuario');

                            $arr = array(
                                "idproducto" => $idproducto,
                                "fecha"      => $fecha,
                                "idusuario"  => $idusuario,
                                "actual" => array(
                                    "codigo"      => $producto['codigo'],
                                    "nombre"      => $producto['nombre'],
                                    "descripcion" => $producto['descripcion'],
                                    "idcategoria" => $producto['idcategoria'],
                                    "stock"       => $producto['stock'],
                                    "min"         => $producto['min'],
                                    "max"         => $producto['max'],
                                    "ubicacion"   => $producto['ubicacion'],
                                    "idum"        => $producto['idum'],
                                ),
                                "update" => array(
                                    "codigo"      => $codigo,
                                    "nombre"      => $nombre,
                                    "descripcion" => $descripcion,
                                    "idcategoria" => $categoria,
                                    "stock"       => $stock,
                                    "min"         => $min,
                                    "max"         => $max,
                                    "ubicacion"   => $ubicacion,
                                    "idum"        => $medida,
                                )
                            );

                            $file = fopen("public/updates/productos.txt", "a");
                            fwrite($file, json_encode($arr, JSON_UNESCAPED_UNICODE).PHP_EOL);
                            fclose($file);                            
                            //FIN GUARDAR MOVIMIENTO UPDATE
                        }
                    }                    
                }else{
                    //insert
                    $idusuario = session('idusuario');
                    $existe = $this->modeloProducto->existeCodigoPro($codigo);
                    if($existe['total'] > 0){
                        $msg_err = "El código ya existe";
                        echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
                    }else{
                        $insert = $this->modeloProducto->insertProducto($codigo,$nombre,$categoria,$medida,$stock,$min,$max,$ubicacion,$descripcion,$idusuario);
                        
                        echo json_encode(array("err"=> $msg_err, "res" => "insert $insert"),JSON_UNESCAPED_UNICODE);
                    }              
                }
            }else{
                echo json_encode(array("err"=> $msg_err, "res" => ""),JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function uploadImagen($idproducto){
        if(session('idtipousu') == 3){
            return redirect()->to('dashboard');
        }

        $data['title']         = 'Imagen Producto';
        $data['contenido']     = 'producto/imagenproducto';
        $data['li_productos']  = true;
        $data['act_productos'] = true;

        $producto = $this->modeloProducto->getProducto($idproducto);
        if($producto){
            $path = "public/images/products/$idproducto";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $data['producto'] = $producto;
            return view('template/layout', $data);
        }
    }

    public function processImage(){
        if( $this->request->isAJAX() ){
            $idproducto = $_POST['idproducto'];
            /* print_r($_FILES);
            print_r($_POST);exit; */

            $producto = $this->modeloProducto->getProducto($idproducto);
            if( !$producto ){
                echo "PRODUCTO NO ENCONTRADO";
                exit();
            }
            if( $producto['img'] != NULL ){
                echo "EL PRODUCTO YA TIENE UNA IMAGEN, ELIMINE LA IMAGEN PARA SUBIR OTRA";
                exit();
            }            

            helper(['form', 'url']); 
   
            $validateImg = $this->validate([
                'image' => [
                    'uploaded[image]',
                    'mime_in[image,image/jpg,image/jpeg,image/png,image/gif,application/pdf]',
                    'max_size[image,4096]',
                ]
            ]);
       
            if (!$validateImg) {
                print_r('IMAGEN INCORRECTA!!');
            } else {
                $x_file = $this->request->getFile('image');
                $name_img = $x_file->getName();

                //print_r($x_file->getClientMimeType());exit();
                if( $x_file->getClientMimeType() != 'application/pdf' ){
                    $image = \Config\Services::image()
                    ->withFile($x_file)
                    ->resize(800, 800, true, 'height')
                    ->save('public/images/products/'.$idproducto.'/'.$name_img);
    
                    $x_file->move(WRITEPATH . 'uploads');
    
                    /* $fileData = [
                        'name' =>  $x_file->getName(),
                        'type'  => $x_file->getClientMimeType()
                    ];
                    print_r($fileData); */
                }else if( $x_file->getClientMimeType() == 'application/pdf' ){
                    $x_file->move('public/images/products/'.$idproducto.'');
                }

                $this->modeloProducto->updateImagen($idproducto, $name_img);
                print_r('OK');
                
            }
        }
    }

    public function deleteImagen($idproducto){
        $producto = $this->modeloProducto->getProducto($idproducto);
        if( !$producto ){
            return redirect()->to( '/productos' );
        }
        if($producto){
            unlink('public/images/products/'.$idproducto.'/'.$producto['img']);
            $this->modeloProducto->updateImagen($idproducto, '');
            return redirect()->to( '/imagen-producto-'.$idproducto );
        }
    }

    public function deleteProducto(){
        if(session('idtipousu') == 3){
            exit();
        }

		if( $this->request->isAJAX() ){
			$idproducto = $_POST['idproducto'];
			$producto = $this->modeloProducto->getProducto($idproducto);
			if( $producto ){
				$respuesta = "";
				/*** COMPROBAR SI TIENE COMPRAS */
				$compra = $this->modeloProducto->existeEnCompra($idproducto);
				/*** COMPROBAR SI TIENE VENTAS */
				$venta = $this->modeloProducto->existeEnVenta($idproducto);

				if($compra['total'] > 0){
					$respuesta = "El producto tiene $compra[total] entrada(s). ";
				}
				if($venta['total'] > 0){
					$respuesta .= "El producto tiene $venta[total] salida(s). ";
				}

				if(trim($respuesta) == ""){
                    if($producto['img'] != null){
                        unlink('public/images/products/'.$idproducto.'/'.$producto['img']);
                        $this->modeloProducto->updateImagen($idproducto, '');
                    }
					if($this->modeloProducto->deleteProducto($idproducto))
						$respuesta = "eliminado";
				}	
				echo $respuesta;			
			}
		}
	}


    public function kardex(){
        $data['title']         = 'Kardex';
        $data['contenido']     = 'producto/kardex';
        $data['li_productos']  = true;
        $data['act_kardex'] = true;

        return view('template/layout', $data);
    }

    public function movimientos($idproducto){
        $data['title']         = 'Movimientos';
        $data['contenido']     = 'producto/movimientos';
        $data['li_productos']  = true;
        $data['act_kardex'] = true;

        $producto = $this->modeloProducto->getProducto($idproducto);
        $data['producto'] = $producto;

        if( !$producto ){
            return redirect()->to( '/kardex' );
        }

        /* $mov = $this->modeloProducto->detalle_kardex($idproducto);
        $data['movimientos'] = $mov; */

        return view('template/layout', $data);
    }

    public function movimientosDT($idproducto){
        if( $this->request->isAJAX() ){
			/* echo json_encode($_POST,JSON_UNESCAPED_UNICODE);
			print_r($_POST); */
            $fecha_ini = trim($_POST['fecha_ini']);
            $fecha_fin = trim($_POST['fecha_fin']);
			$mov = $this->modeloProducto->detalle_kardex($idproducto, $fecha_ini, $fecha_fin);
			print json_encode($mov, JSON_UNESCAPED_UNICODE);
        }
    }

    public function detalleMov(){
        if( $this->request->isAJAX() ){
            $id         = $_POST['id'];
            $mov        = $_POST['mov'];
            $idproducto = $_POST['idproducto'];
            
            if( $mov == 'entrada' ){
                $head     = $this->modeloEntrada->getEntrada($id);
                $detalle = $this->modeloEntrada->getDetalle($id);
            }else if( $mov == 'salida' ){
                $head     = $this->modeloSalida->getSalida($id);
                $detalle = $this->modeloSalida->getDetalle($id);
            }

            $data['head']       = $head;
            $data['detalle']    = $detalle;
            $data['mov']        = $mov;
            $data['idproducto'] = $idproducto;
            
            return view('producto/modalDetalle', $data);
        }
    }

    public function detalleExcel(){
        $detalle = $this->modeloProducto->kardexAExcel();

        $data['detalle'] = $detalle;

        return view('producto/aexcel', $data);
    }
    
}