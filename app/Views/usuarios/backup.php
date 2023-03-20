<style>
    .ul-backups{
        list-style: none;
        padding: 0;
    }
    .ul-backups li{
        padding: 5px 3px;
    }
    .ul-backups li:nth-child(odd){
        background-color: #eee;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Backup BD</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-6 mb-4">
                <button class="btn btn-primary" role="button" id="btnBackup">
                    Crear Backup
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?php
                obtener_estructura_directorios("public/backups");
                ?>
            </div>
        </div>
    </div>
</div>


<script>
$(function(){
    $("#btnBackup").on('click', function(e){
        e.preventDefault();
        let btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>',
            textBtn = $('#btnBackup').text();
        $("#btnBackup").attr('disabled', 'disabled');
        $("#btnBackup").html(`${btnHTML} Generando...`);
        $.ajax({
            method: 'POST',
            url: 'usuario/generarBackup',
            //data: '',
            success: function(datos){
                //console.log(datos);
                
                swal_alert('Mensaje', "Backup Creado...!", 'info', 'Aceptar');

                setTimeout(() => {
                    location.reload();
                }, 2000);

                $("#btnBackup").removeAttr('disabled');
                $("#btnBackup").text(textBtn);
            }
        });
    });
});
</script>


<?php

function obtener_estructura_directorios($ruta){
    // Se comprueba que realmente sea la ruta de un directorio
    if (is_dir($ruta)){
        // Abre un gestor de directorios para la ruta indicada
        $gestor = opendir($ruta);
        echo "<ul class='ul-backups'>";

        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {
                
            $ruta_completa = $ruta . "/" . $archivo;

            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
                // Si es un directorio se recorre recursivamente
                if (is_dir($ruta_completa)) {
                    echo "<li>" . $archivo . "</li>";
                    obtener_estructura_directorios($ruta_completa);
                } else {
                    echo "<li><a href='elimina-backup-".$archivo."'><i class='fa fa-trash-alt'></i></a> &nbsp;<a target='_blank' href='".$ruta_completa."'>" . $archivo . "</a></li>";
                }
            }
        }
        
        // Cierra el gestor de directorios
        closedir($gestor);
        echo "</ul>";
    } else {
        echo "No es una ruta de directorio valida<br/>";
    }
}
?>