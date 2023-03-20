<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    </head>
<body>
    

<?php

    $data = $detalle;

    function cleanData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    // file name for download
    $filename = "movimientos_" . date('Ymd') . ".xls";

    header("Content-Encoding: UTF-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");

    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    $cabeceras = [];
    foreach($data as $i => $v){
        $cabeceras[] = array_keys($v);
        break;
    }
    //print_r($cabeceras);
    echo "<h2>Movimientos</h2>";
    echo "<table>";

    echo "<tr>";
    foreach($cabeceras[0] as $c){
        echo "<th>$c</th>";
    }
    echo "</tr>";

    foreach($data as $d){
        echo "<tr>";
        foreach($cabeceras[0] as $c){
            echo "<td>$d[$c]</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    
?>
</body>
</html>