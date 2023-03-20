<style>
    .status-btn{
        cursor:pointer;
        color: white!important; 
        padding: 0 10px; 
        border-radius: 3px
    }
    .status-ok{background-color:green;}
    .status-bad{background-color:red;}
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Usuarios</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-6 mb-4">
                <a class="btn btn-primary" href="nuevo-usuario" role="button">
                    Nuevo Usuario
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive tablas" width="100%" id="tblUsuarios">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>USUARIO</th>
                        <th>NOMBRES</th>
                        <th>DNI</th>
                        <th>ESTADO</th>
                        <th>TIPO</th>
                        <th>AREA</th>
                        <th>OPCION</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($usuarios){
                            foreach($usuarios as $usu){
                                $idusuario = $usu['idusuario'];
                                $usuario   = $usu['usuario'];
                                $nombres   = $usu['nombres'];
                                $dni       = $usu['dni'];
                                $status    = $usu['status'];
                                $idtipousu = $usu['idtipousu'];
                                $tipo      = $usu['tipo'];
                                $area      = $usu['area'];

                                if($status == 1 && ($idtipousu == 2 || $idtipousu == 3) ){
                                    $estado = "<a class='btnStatus status-btn status-ok' data-status=$status data-idusuario=$idusuario>activo</a>";
                                }else if($status == 0 && ($idtipousu == 2 || $idtipousu == 3) ){
                                    $estado = "<a class='btnStatus status-btn status-bad' data-status=$status data-idusuario=$idusuario>inactivo</a>";
                                }else if($idtipousu == 1){
                                    $estado = "activo";
                                }

                                echo "<tr>";
                                echo "<td>$idusuario</td>";
                                echo "<td>$usuario</td>";
                                echo "<td>$nombres</td>";
                                echo "<td>$dni</td>";
                                echo "<td>$estado</td>";
                                echo "<td>$tipo</td>";
                                echo "<td>$area</td>";
                                echo "<td>";
                                    echo "<a title='editar' class='btn btn-success btn-sm' href='edit-usuario-$idusuario' role='button'>
                                        <i class='fa fa-edit'></i>
                                    </a> ";
                                    if($idtipousu != 1){
                                    echo "<a title='borrar' class='btn btn-danger btn-sm deleteUsuario' data-idusuario='$idusuario' data-usuario='".$usuario."' role='button' href=''>
                                        <i class='fa fa-trash-alt'></i>
                                    </a>";
                                    }
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<div id="msjU"></div>

<script>
$(function(){
    $('#tblUsuarios').on('click', '.btnStatus', function(e) {
        let status = $(this).attr('data-status'),
            idusuario = $(this).attr('data-idusuario');
        
        if(status == 1){
            $(this).removeClass('status-ok').addClass('status-bad');
            $(this).attr('data-status', 0);
            $(this).text('inactivo');
        }else if(status == 0){
            $(this).removeClass('status-bad').addClass('status-ok');
            $(this).attr('data-status', 1);
            $(this).text('activo');
        }
        $.post('usuario/updateStatusUsuario', {
            idusuario, status
        }, function(data){
            console.log(data);
        })
    });

    $('#tblUsuarios').on('click', '.deleteUsuario', function(e){
        e.preventDefault();
        let idusuario = $(this).data('idusuario'),
            usuario = $(this).data('usuario');

        //console.log(idusuario, usuario);
        let objConfirm = {
            title: 'Atención',
            text: "¿VAS A ELIMINAR AL USUARIO: "+usuario+"?",
            icon: 'warning',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('usuario/eliminarUsuario', {
                    idusuario, usuario
                }, function(data){
                    $("#msjU").html(data);
                    console.log(data);
                });
            }
        }            
        swal_confirm(objConfirm);
    });
})
</script>