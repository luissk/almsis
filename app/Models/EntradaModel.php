<?php 
namespace App\Models;

use CodeIgniter\Model;

class EntradaModel extends Model{
    
    //MANTTO ENTRADA
    public function insertEntrada($fechareg, $documento, $comentario, $idusuario){
        $query = "insert into entrada(fecha,documento,comentario,status,idusuario) values('".$fechareg."', '".$documento."', '".$comentario."', 1, $idusuario)";
        $st = $this->db->query($query);

        return $this->db->insertID();
    }

    public function insertDetalleEntrada($identrada,$idproducto,$cantidad){
        $query = "insert into detalle_entrada(idproducto,identrada,cantidad) values($idproducto,$identrada,$cantidad)";
        $st = $this->db->query($query);

        return $st;
    }

    public function getEntradas($desc = ''){
        $condicion = "";
        if($desc != null){
            $condicion .= "and EXISTS (
                select de.identrada 
                from detalle_entrada de
                inner join producto pro on de.idproducto=pro.idproducto 
                where de.identrada=e.identrada and pro.nombre like '%".$desc."%'
            )";
        }

        $query = "select e.identrada,e.fecha,e.fechareg,e.documento,e.comentario,e.status 
        from entrada e 
        where e.identrada is not null $condicion order by e.identrada desc";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getEntrada($identrada){
        $query = "select * from entrada where identrada=$identrada";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getDetalle($identrada){
        $query = "select de.identrada,de.idproducto,de.cantidad, pro.codigo,pro.nombre,pro.descripcion,pro.stock,pro.idum,pro.idcategoria,pro.img, um.um,cat.categoria from detalle_entrada de inner join producto pro on de.idproducto=pro.idproducto inner join unidadm um on pro.idum=um.idum inner join categoria cat on pro.idcategoria=cat.idcategoria where de.identrada=$identrada";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function updateEntrada($fechareg, $documento, $comentario, $identrada){
        $query = "update entrada set fecha='".$fechareg."',documento='".$documento."',comentario='".$comentario."' where identrada=$identrada";
        $st = $this->db->query($query);

        return $st;
    }

    public function deleteDetalle($identrada){
        $query = "delete from detalle_entrada where identrada=$identrada";
        $st = $this->db->query($query);

        return $st;
    }

    public function deleteEntrada($identrada){
        $query = "delete from entrada where identrada=$identrada";
        $st = $this->db->query($query);

        return $st;
    }

}