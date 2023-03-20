<?php 
namespace App\Models;

use CodeIgniter\Model;

class SalidaModel extends Model{

    //AREAS
    public function getAreas(){
        $query = "select * from area order by idarea desc";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getArea($idarea){
        $query = "select * from area where idarea=$idarea";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function existeArea($area){
        $query = "select count(idarea) total from area where upper(area)=upper('".$area."')";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertArea($area, $nombres, $apellidos, $dni){
        $query = "insert into area(area,nombres,apellidos,dni,status) values('".$area."','".$nombres."','".$apellidos."',$dni,1)";
        $st = $this->db->query($query);

        return $st;
    }

    public function updateArea($area, $nombres, $apellidos, $dni, $idarea){
        $query = "update area set area='".$area."',nombres='".$nombres."',apellidos='".$apellidos."',dni=$dni where idarea=$idarea";
        $st = $this->db->query($query);

        return $st;
    }

    public function existsAreaInSalida($idarea){
        $query = "select count(idarea) total from salida where idarea=$idarea";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function deleteArea($idarea){
        $query = "delete from area where idarea=$idarea";
        $st = $this->db->query($query);

        return $st;
    }

    
    //MANTTO SALIDA
    public function insertSalida($fechareg, $documento, $comentario, $area, $idusuario){
        $query = "insert into salida(fecha,documento,comentario,status,idarea,idusuario) values('".$fechareg."', '".$documento."', '".$comentario."', 1, $area, $idusuario)";
        $st = $this->db->query($query);

        return $this->db->insertID();
    }

    public function insertDetalleSalida($idsalida,$idproducto,$cantidad){
        $query = "insert into detalle_salida(idproducto,idsalida,cantidad) values($idproducto,$idsalida,$cantidad)";
        $st = $this->db->query($query);

        return $st;
    }

    public function getSalidas($desc = ''){
        $condicion = "";
        if($desc != null){
            $condicion .= "and EXISTS (
                select ds.idsalida 
                from detalle_salida ds
                inner join producto pro on ds.idproducto=pro.idproducto 
                where ds.idsalida=s.idsalida and pro.nombre like '%".$desc."%'
            )";
        }

        $query = "select s.idsalida,s.fecha,s.fechareg,s.documento,s.idarea,s.comentario,s.status,
        ar.area,ar.nombres,ar.apellidos,ar.dni 
        from salida s 
        inner join area ar on s.idarea=ar.idarea
        where s.idsalida is not null $condicion order by s.idsalida desc";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function getSalida($idsalida){
        $query = "select s.idsalida,s.fecha,s.fechareg,s.documento,s.idarea,s.comentario,s.status,
        ar.area,ar.nombres,ar.apellidos,ar.dni 
        from salida s 
        inner join area ar on s.idarea=ar.idarea
         where s.idsalida=$idsalida";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function getDetalle($idsalida){
        $query = "select ds.idsalida,ds.idproducto,ds.cantidad, pro.codigo,pro.nombre,pro.descripcion,pro.stock,pro.idum,pro.idcategoria,pro.img, um.um,cat.categoria,
        (select ifnull(sum(dsa.cantidad),0) from detalle_salida dsa inner join salida s on dsa.idsalida=s.idsalida where dsa.idproducto=pro.idproducto and s.status=1) as nrosalidas,
        (select ifnull(sum(de.cantidad),0) from detalle_entrada de where de.idproducto=pro.idproducto) as nroentradas 
        from detalle_salida ds 
        inner join producto pro on ds.idproducto=pro.idproducto 
        inner join unidadm um on pro.idum=um.idum 
        inner join categoria cat on pro.idcategoria=cat.idcategoria 
        where ds.idsalida=$idsalida";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function updateSalida($fechareg, $documento, $comentario, $idsalida, $idarea){
        $query = "update salida set fecha='".$fechareg."',documento='".$documento."',comentario='".$comentario."',idarea=$idarea where idsalida=$idsalida and status=1";
        $st = $this->db->query($query);

        return $st;
    }

    public function deleteSalida($idsalida){
        $query = "delete from salida where idsalida=$idsalida";
        $st = $this->db->query($query);

        return $st;
    }

    public function deleteDetalle($idsalida){
        $query = "delete from detalle_salida where idsalida=$idsalida";
        $st = $this->db->query($query);

        return $st;
    }

    public function getDetalleSalida($idsalida, $idproducto){
        $query = "select * from detalle_salida where idsalida=$idsalida and idproducto=$idproducto";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    //DASHBOARD
    public function grafica_salidas(){
        $query = "select year(fecha) anio, month(fecha) mes, date_format(fecha, '%b') nom_mes,
        count(idsalida) as total
        from salida
        where fecha <= now() and fecha >= date_add(now(), interval - 6 month)
        GROUP by date_format(fecha, '%m-%Y') 
        order by anio ASC, mes asc";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function topProductosMov($nro = 4){
        $query = "select p.idproducto,p.codigo, p.nombre, COUNT(sa.idsalida) salidas
        from producto p 
        inner join detalle_salida ds on p.idproducto=ds.idproducto
        inner join salida sa on ds.idsalida=sa.idsalida 
        GROUP by p.idproducto,p.codigo, p.nombre
        ORDER BY salidas desc
        limit 0,$nro";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }
}