<?php 
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model{
    

    public function validaUsuario($usuario){
        $query = "select * from usuario where usuario='".$usuario."' and status=1";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function tiposDeUsuario(){
        $query = "select * from tipousuario where status=1";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function listarUsuarios(){
        $query = "select usu.idusuario,usu.usuario,usu.nombres,usu.dni,usu.status,usu.idtipousu,usu.idarea,tus.tipo,ar.area
        from usuario usu 
        inner join tipousuario tus on usu.idtipousu=tus.idtipousu
        left join area ar on usu.idarea = ar.idarea";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function updateStatusUsuario($idusuario, $estado){
        $query = "update usuario set status=$estado where idusuario=$idusuario and idtipousu != 1";
        $st = $this->db->query($query);

        return $st;
    }

    public function getUsuario($idusuario){
        $query = "select usu.idusuario,usu.usuario,usu.password,usu.nombres,usu.dni,usu.status,usu.idtipousu,usu.idarea,tus.tipo,ar.area
        from usuario usu 
        inner join tipousuario tus on usu.idtipousu=tus.idtipousu 
        left join area ar on usu.idarea = ar.idarea
        where usu.idusuario=$idusuario";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function existeUsuarioUsu($usuario){
        $query = "select count(idusuario) total from usuario where usuario='".$usuario."'";
        $st = $this->db->query($query);

        return $st->getRowArray();
    }

    public function insertUsuario($usuario,$password,$nombres,$dni,$tusuario,$idarea){
        $query = "insert into usuario(usuario,password,nombres,dni,status,idtipousu,idarea) values('".$usuario."','".$password."','".$nombres."','".$dni."',1,$tusuario,$idarea)";
        $st = $this->db->query($query);

        return $this->db->insertID();
    }

    public function updateUsuario($usuario,$password,$nombres,$dni,$idusuario,$tusuario,$idarea){
        $query = "update usuario set usuario='".$usuario."', password='".$password."', nombres='".$nombres."', dni='".$dni."',idtipousu=$tusuario, idarea=$idarea
        where idusuario=$idusuario";
        $st = $this->db->query($query);

        return $st;
    }

    
    public function vericaUsuEnProducto($idusuario){
        $query = "select idusuario from producto where idusuario=$idusuario";
        $st = $this->db->query($query);
        
        return $st->getResultArray();
    }

    public function vericaUsuEnEntrada($idusuario){
        $query = "select idusuario from entrada where idusuario=$idusuario";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function vericaUsuEnSalida($idusuario){
        $query = "select idusuario from salida where idusuario=$idusuario";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function vericaUsuEnRequerimiento($idusuario){
        $query = "select idusuario from requerimiento where idusuario=$idusuario or idusuario2=$idusuario";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function eliminaUsuario($idusuario){
        $query = "delete from usuario where idusuario=$idusuario";
        $st = $this->db->query($query);

        return $st;
    }

    
}