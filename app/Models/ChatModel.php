<?php 
namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model{
    
    public function listarUsuarios($idusuario){
        $query = "select usu.idusuario,usu.usuario,usu.nombres,usu.dni,usu.status,usu.idtipousu,usu.idarea,tus.tipo,ar.area
        from usuario usu 
        inner join tipousuario tus on usu.idtipousu=tus.idtipousu
        left join area ar on usu.idarea = ar.idarea 
        where usu.idusuario != $idusuario and usu.status = 1";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function cargarChat($emisor, $receptor){
        $query = "select ch.idchat, ch.emisor, ch.receptor, ch.fecha, ch.mensaje, ch.status, ch.leido,
        usue.usuario usuemi, usue.nombres nomemi, usur.usuario usurec, usur.nombres nomrec
        from chat ch 
        inner join usuario usue on ch.emisor = usue.idusuario 
        inner join usuario usur on ch.receptor = usur.idusuario
        where 
        (
            (ch.emisor = $emisor and ch.receptor = $receptor) 
            or 
            (ch.emisor = $receptor and ch.receptor = $emisor)
        )
        and 
        (
        	(ch.status = 0 or ch.status != $emisor) and ch.status != -1
        )";
        $st = $this->db->query($query);

        return $st->getResultArray();
    }

    public function insertMessage($emisor, $receptor, $mensaje){
        $query = "insert into chat(emisor, receptor, mensaje) 
            values($emisor, $receptor, '".$mensaje."')";
        $st = $this->db->query($query);

        return $st;
    }

    public function borrarChat($emisor, $receptor){
        $query = "update chat set status = case 
                when status = 0 then $emisor else -1 end 
            where 
            (
                (emisor = $emisor and receptor = $receptor) 
                or 
                (emisor = $receptor and receptor = $emisor)
            )";
        $st = $this->db->query($query);

        return $st;
    }

    public function actualizaChatLeido($idchat){
        $query = "update chat set leido = 1 where idchat=$idchat";
        $st = $this->db->query($query);

        return $st;
    }

}