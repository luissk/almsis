<?php 
namespace App\Models;

use CodeIgniter\Model;

class Articulo extends Model{
    protected $table      = 'articulos';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion', 'precio', 'stock'];

    public function getArticulo($id){
        $query = "select * from articulos where id = $id";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }
}