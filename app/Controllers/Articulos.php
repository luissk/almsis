<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Articulo;
class Articulos extends Controller{

    public function __construct(){
        $this->modeloArticulo = model('Articulo');
    }


    public function index(){

        $articulos = new Articulo();
        $datos['libros'] = $articulos->orderBy('id', 'ASC')->findAll();

        $datos['title']     = 'Listado de artículos';
        $datos['contenido'] = 'listado';
        return view('template/content', $datos);
    }

    public function crear(){       
       /*  $one = $this->modeloArticulo->getArticulo(3);
        print_r($one); */

        $datos['title']     = 'Crear artículos';
        $datos['contenido'] = 'crear';
        return view('template/content', $datos);
    }

    public function guardar(){
        $descripcion = $this->request->getVar('descripcion');
        $precio      = $this->request->getVar('precio');
        $stock       = $this->request->getVar('stock');

        $articulo = new Articulo();

        $datos = [
            'descripcion' => $descripcion,
            'precio' => $precio,
            'stock' => $stock
        ];

        if( $articulo->insert($datos) ){
            echo "GUARDADO";
        }
    }
}

/*

as per CI 4

use

return redirect()->to('url'); 
if you are using route then use

return redirect()->route('named_route');
*/