<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\EntradaModel;
use App\Models\SalidaModel;
use App\Models\RequerimientoModel;
class Requerimiento extends Controller{

    protected $helpers = ['funciones'];

    public function __construct(){
        $this->modeloUsuario       = model('UsuarioModel');
        $this->modeloProducto      = model('ProductoModel');
        $this->modeloEntrada       = model('EntradaModel');
        $this->modeloSalida        = model('SalidaModel');
        $this->modeloRequerimiento = model('RequerimientoModel');
        $this->session = \Config\Services::session();
    }

    public function notificacionReq(){
        if( $this->request->isAJAX() ){
            $req= $this->modeloRequerimiento->countReq(3);
            if($req){
                echo $req['total'];
            }
        }
    }

    // REQUERIMIENTOS
    public function requerimiento(){
        $data['title']             = 'Requerimientos';
        $data['li_productos']      = true;
        $data['act_requerimiento'] = true;

        if(!session('idusuario')) return redirect()->to('dashboard');

        $usuario = $this->modeloUsuario->getUsuario(session('idusuario'));
        $data['usuario'] = $usuario;
        
        if(session('idtipousu') == 1 || session('idtipousu') == 2){
            $data['requerimientos'] = $this->modeloRequerimiento->listarRequerimientos();
            $data['contenido'] = 'requerimiento/atender';
        }else if(session('idtipousu') == 3){
            $producto = $this->modeloProducto->getProductos();
            $data['productos']      = $producto;
            
            $data['requerimientos'] = $this->modeloRequerimiento->listarRequerimientos($usuario['idarea']);
            $data['contenido']      = 'requerimiento/hacer';
        }

        return view('template/layout', $data);
    }

    public function agregarProducto(){
        if( $this->request->isAJAX() ){
            $codigopro = trim($_POST['codigopro']);

            if($codigopro != ''){
                $producto = $this->modeloProducto->getProductoPorCodigo($codigopro);
                if($producto){
                    $idproducto = $producto['idproducto'];
                    $codigo     = $producto['codigo'];
                    $nombre     = $producto['nombre'];
                    $stock      = $producto['stock'] + $producto['nroentradas'] - $producto['nrosalidas'];
                    $um         = $producto['um'];

                    if($stock < 1){
                        echo "<script>swal_alert('Producto sin stock', '".$nombre."', 'warning', 'Aceptar')</script>";
                        exit();
                    }

                    $arr = array(
                        "idproducto" => $idproducto,
                        "codigo"     => $codigo,
                        "nombre"     => $nombre,
                        "stock"      => $stock,
                        "um"         => $um,
                        "cantidad"   => 1
                    );

                    echo json_encode($arr, true);
                }else{
                    echo "<script>swal_alert('Error', 'El código no existe!!', 'warning', 'Aceptar')</script>";
                }
            }
        }
    }

    public function procesaRequerimiento(){
        if( $this->request->isAJAX() ){
            //print_r($_POST);exit();
            $fecha      = trim($_POST['fecha']);
            $codigo     = trim($_POST['codigo']);
            $comentario = trim($_POST['comentario']);
            $items      = json_decode($_POST['items'], true);
            //print_r($items);
            $idreqhidden = $_POST['idreqhidden']; //PARA EDITAR

            $msj = ""; // para validar

            if($fecha == '') $msj = "Ingrese una Fecha";
            else if($codigo == '') $msj = "El código es requerido";
            else if($comentario == '') $msj = "Ingrese un comentario";
            else if(count($items) < 1) $msj = "Debe agregar productos";

            /*** 0. VERIFICAR STOCK ***/
            foreach($items as $item){
                $idproducto   = $item['idproducto'];
                $cantidad     = $item['cantidad'];
                
                $producto = $this->modeloProducto->getProducto($idproducto);
                $stockact = $producto['stock'] + $producto['nroentradas'] - $producto['nrosalidas'];

                if($cantidad < 1){
                    $msj = "Cantidad inválida del producto: ".$producto['codigo'];
                    break;
                }

                if( $cantidad > $stockact ){
                    $msj = "La cantidad sobrepasa al stock del producto ".$producto['codigo'];
                    break;
                }
            }

            if($msj != ''){                
                echo "<script>swal_alert('Atención', '".$msj."', 'warning', 'Aceptar')</script>";
                exit();
            }

            if( $idreqhidden != '' && $reqE = $this->modeloRequerimiento->getRequerimiento($idreqhidden) ){//PARA EDITAR
                //print_r($reqE);exit();
                $estado    = $reqE['estado'];
                $lblEstado = "No puedes modificar el requerimiento $codigo, tiene como estado: ".h_estadoReq($estado).". Contacta con almacén.";
                if( $estado != 3 ){ // VERIFICAR SI YA CAMBIO DE ESTADO
                    echo "<script>swal_alert('', '".$lblEstado."', 'warning', 'Aceptar')</script>";
                    exit();
                }

                $ok = FALSE;
                if( $this->modeloRequerimiento->editReq($idreqhidden, $comentario) ){
                    if($this->modeloRequerimiento->eliminarDetalleRe($idreqhidden)){
                        foreach($items as $item){
                            $idproducto   = $item['idproducto'];
                            $cantidad     = $item['cantidad'];
                            
                            $producto = $this->modeloProducto->getProducto($idproducto);
                            if($producto){                                
                                if($this->modeloRequerimiento->insertDetalleReq($idreqhidden,$idproducto,$cantidad)){
                                    $ok = TRUE;
                                }
                            }
                        }
                    }
                }
                if($ok) {
                    echo "<script>$('#btnRequerimiento').attr('disabled', 'disabled')</script>";//deshabilitar el boton en caso le de de nuevo xD          
                    echo "<script>swal_alert('Mensaje', 'SE CREÓ MODIFICO EL REQUERIMIENTO: ".$codigo."', 'success', 'Aceptar')</script>";
                    echo "<script>setTimeout(function(){location.reload()}, 2500)</script>";
                }

            }else{ // PARA INSERTAR

                $ok = FALSE;
                /*** 1. CREAR REQUERIMIENTO ***/
                $idusuario = session('idusuario');
                $user = $this->modeloUsuario->getUsuario($idusuario);
                //print_r($user);
                $idarea = $user['idarea'];
                $area   = $user['area'];
                
                $idreq = $this->modeloRequerimiento->insertReq($fecha, $codigo, $comentario, $idusuario, $idarea, $area);

                if($idreq > 0){
                    /*** 2. RECORRER ITEMS ***/
                    foreach($items as $item){
                        $idproducto   = $item['idproducto'];
                        $cantidad     = $item['cantidad'];
                        /*** 3. BUSCAR PRODUCTO ***/
                        $producto = $this->modeloProducto->getProducto($idproducto);
                        if($producto){
                            /*** 4. INSERTAR DETALLE REQ ***/
                            if($this->modeloRequerimiento->insertDetalleReq($idreq,$idproducto,$cantidad)){
                                $ok = TRUE;
                            }
                        }
                    }
                    if($ok) {
                        echo "<script>$('#btnRequerimiento').attr('disabled', 'disabled')</script>";//deshabilitar el boton en caso le de de nuevo xD          
                        echo "<script>swal_alert('Mensaje', 'SE CREÓ EL REQUERIMIENTO CON CODIGO: ".$codigo."', 'success', 'Aceptar')</script>";
                        echo "<script>setTimeout(function(){location.reload()}, 2500)</script>";
                    }
                    
                }

            }

        }
    }

    public function modalDetalleReq(){
        if( $this->request->isAJAX() ){
            $idreq = $_POST['idreq'];
            
            $req = $this->modeloRequerimiento->getRequerimiento($idreq);
            if($req){
                $detalle = $this->modeloRequerimiento->listarDetalleReq($idreq);

                $data['req']     = $req;
                $data['detalle'] = $detalle;
                return view('requerimiento/detallereq', $data);
            }else{
                echo "NO EXISTE EL REQUERIMIENTO";
            }
        }
    }

    public function eliminareReq(){
        if( $this->request->isAJAX() ){
            $idreq = $_POST['idreq'];
            
            $req = $this->modeloRequerimiento->getRequerimiento($idreq);
            if($req){
                $codigo    = $req['codigo'];
                $estado    = $req['estado'];
                $lblEstado = "No puedes eliminar el requerimiento $codigo, tiene como estado: ".h_estadoReq($estado).". Contacta con almacén.";

                if( $estado == 1 || $estado == 2 ){
                    echo "<script>swal_alert('', '".$lblEstado."', 'warning', 'Aceptar')</script>";
                }else{
                    if($this->modeloRequerimiento->eliminarReq($idreq)){
                        if($this->modeloRequerimiento->eliminarDetalleRe($idreq)){
                            echo "<script>swal_alert('Mensaje', 'SE ELIMINÓ EL REQUERIMIENTO CON CODIGO: ".$codigo."', 'success', 'Aceptar')</script>";
                            echo "<script>setTimeout(function(){location.reload()}, 2500)</script>";
                        }
                    }
                }
            }else{
                echo "<script>swal_alert('Mensaje', 'NO EXISTE EL REQUERIMIENTO', 'warning', 'Aceptar')</script>";
            }
        }
    }

    public function editarReq(){
        if( $this->request->isAJAX() ){
            $idreq = $_POST['idreq'];
            
            $req = $this->modeloRequerimiento->getRequerimiento($idreq);
            if($req){
                $codigo    = $req['codigo'];
                $estado    = $req['estado'];
                $lblEstado = "No puedes modificar el requerimiento $codigo, tiene como estado: ".h_estadoReq($estado).". Contacta con almacén.";

                if( $estado == 1 || $estado == 2 ){
                    echo "<script>swal_alert('', '".$lblEstado."', 'warning', 'Aceptar')</script>";
                }else{
                    $idreq      = $req['idreq'];
                    $estado     = $req['estado'];
                    $idarea     = $req['idarea'];
                    $fecha      = $req['fecha'];
                    $comentario = $req['comentario'];

                    $detalle = $this->modeloRequerimiento->listarDetalleReqPorCodigo($codigo);//mas datos

                    $arr = array(
                        "idreq"      => $idreq,
                        "codigo"     => $codigo,
                        "estado"     => $estado,
                        "idarea"     => $idarea,
                        "fecha"      => $fecha,
                        "comentario" => $comentario,
                        "items"      => $detalle
                    );

                    echo json_encode($arr, true);
                }
            }else{
                echo "<script>swal_alert('Mensaje', 'NO EXISTE EL REQUERIMIENTO', 'warning', 'Aceptar')</script>";
            }
        }
    }

    public function cambiarEstado(){
        if( $this->request->isAJAX() ){
            $idreq     = $_POST['idreq'];
            $cboEstado = $_POST['cboEstado'];

            if($cboEstado == ''){
                echo "<script>swal_alert('', 'Seleccione un Estado', 'error', 'Aceptar')</script>";
                exit();
            }

            $idusuario = session('idusuario');
            $user = $this->modeloUsuario->getUsuario($idusuario);
            $idusuario2 = $idusuario;
            $usuario2   = $user['idusuario'];
            $nombres2   = $user['nombres'];

            $req = $this->modeloRequerimiento->getRequerimiento($idreq);
            if($req){
                $estadoDB     = $req['estado'];
                $usuario2DB   = $req['usuario2'];
                $idusuario2DB = $req['idusuario2'];
                //VALIDAR SI YA ESTA SIENDO ATENDIDO POR OTRO USUARIO
                if($idusuario2DB != '' && $idusuario2 != $idusuario2DB){
                    echo "<script>swal_alert('Mensaje', 'El Requerimiento ya ha sido tomado por el usuario: ".$usuario2DB."', 'warning', 'Aceptar')</script>";
                    exit();
                }

                $ok = TRUE;
                if( $estadoDB == 3 ){
                    //PASAR A ESTADO 2
                    //VALIDAR SI EL ESTADO SELECCIONADO ES 2
                    if( $cboEstado != 2 ){
                        echo "<script>swal_alert('Mensaje', 'Solo debes cambiar al Estado: ".h_estadoReq(2)."', 'warning', 'Aceptar')</script>";
                        $ok = FALSE;
                    }
                }else if( $estadoDB == 2 ){
                    //PASAR A ESTADO 1
                    //VALIDAR SI EL ESTADO SELECCIONADO ES 1
                    if( $cboEstado != 1 && $cboEstado != 3 ){
                        echo "<script>swal_alert('Mensaje', 'Solo debes cambiar al Estado: ".h_estadoReq(1)." o ".h_estadoReq(3)."', 'warning', 'Aceptar')</script>";
                        $ok = FALSE;
                    }
                }else if( $estadoDB == 1 ){
                    echo "<script>swal_alert('Mensaje', 'El Requerimiento ya ha sido: ".h_estadoReq(1).". Si quieres cambiarlo, comunícate con Almacén', 'warning', 'Aceptar')</script>";
                    $ok = FALSE;
                }

                if($ok){
                    $cambiaEstado = $this->modeloRequerimiento->cambiarEstado($idreq, $cboEstado, $idusuario2);
                    if($cambiaEstado){
                        echo "<script>swal_alert('Mensaje', 'SE CAMBIÓ DE ESTADO', 'success', 'Aceptar')</script>";
                        echo "<script>setTimeout(function(){location.reload()}, 2500)</script>";
                    }
                }

            }else{
                echo "<script>swal_alert('Mensaje', 'NO EXISTE EL REQUERIMIENTO', 'warning', 'Aceptar')</script>";
            }

        }
    }


    public function buscarReqSalida(){
        if( $this->request->isAJAX() ){
            $codigo = trim($_POST['codigoReq']);
            if($codigo == ''){
                echo "<script>swal_alert('Mensaje', 'Ingrese un código de requerimiento', 'error', 'Aceptar')</script>";
                exit();
            }

            $req = $this->modeloRequerimiento->getRequerimientoPorCodigo($codigo);
            if($req){
                //print_r($req);
                $idreq  = $req['idreq'];
                $estado = $req['estado'];
                $idarea = $req['idarea'];

                if($estado != 1){
                    echo "<script>swal_alert('Solo requerimientos entregados.', 'El requerimiento tiene estado: ".h_estadoReq($estado)."', 'error', 'Aceptar')</script>";
                    exit();
                }

                $detalle = $this->modeloRequerimiento->listarDetalleReqPorCodigo($codigo);

                $arr = array(
                    "idreq"  => $idreq,
                    "codigo" => $codigo,
                    "estado" => $estado,
                    "idarea" => $idarea,
                    "items"  => $detalle
                );

                echo json_encode($arr, true);

            }else{
                echo "<script>swal_alert('Mensaje', 'No existe el requerimiento: ".$codigo."', 'warning', 'Aceptar')</script>";
            }
        }
    }

}