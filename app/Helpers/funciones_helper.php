<?php
if(!defined('APPPATH')) exit('No direct script access allowed');

if(!function_exists('nombre_mes')){
    function nombre_mes($n){
        switch($n){
            case 1: return 'ENERO';
                break;
            case 2: return 'FEBRERO';
                break;
            case 3: return 'MARZO';
                break;
            case 4: return 'ABRIL';
                break;
            case 5: return 'MAYO';
                break;
            case 6: return 'JUNIO';
                break;
            case 7: return 'JULIO';
                break;
            case 8: return 'AGOSTO';
                break;
            case 9: return 'SETIEMBRE';
                break;
            case 10: return 'OCTUBRE';
                break;
            case 11: return 'NOVIEMBRE';
                break;
            default: return 'DICIEMBRE';
        }
    }
}

if(!function_exists('stringAleatorio')){
    function stringAleatorio($length  = 5){
		$characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString     = '';
	    for($i = 0; $i < $length; $i++){
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
    }
}

if(!function_exists('h_estadoReq')){
    function h_estadoReq($idestado, $classColor = ''){
		$estado = "Espera";
        $color = "warning";
        if( $idestado == 2 ){
            $estado = "Atendiendo";
            $color = "info";
        }
        if( $idestado == 1 ){
            $estado = "Entregado";
            $color = "success";
        }

        if($classColor == 'C') return $color;
        
        return $estado;
    }
}

?>