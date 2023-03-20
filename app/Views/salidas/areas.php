<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h4 class="m-0 text-dark">Areas</h4>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid bg-white py-2">
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalArea">
                    Nueva Area
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white mt-2 py-2">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive tablas" width="100%" id="tblAreas">
                    <thead>
                    <tr>
                        <th>AREA</th>
                        <th>ENCARGADO</th>
                        <th>DNI</th>
                        <th>OPCION</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($areas){
                            foreach($areas as $a){
                                $idarea    = $a['idarea'];
                                $area      = $a['area'];
                                $nombres   = $a['nombres'];
                                $apellidos = $a['apellidos'];
                                $dni       = $a['dni'];

                                echo "<tr>";
                                echo "<td>$area</td>";
                                echo "<td>$nombres $apellidos</td>";
                                echo "<td>$dni</td>";
                                echo "<td>
                                <button title='editar' class='btn btn-success btn-sm editArea' idarea=$idarea area='$area' nombres='$nombres' apellidos='$apellidos' dni='$dni'>
                                    editar
                                </button>
                                <button title='eliminar' class='btn btn-danger btn-sm deleteArea' idarea=$idarea area='$area'>
                                    eliminar
                                </button>
                                </td>";
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

<!-- Modal Nueva Area-->
<div class="modal fade" id="modalArea" tabindex="-1" aria-labelledby="titleModalArea" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" id="frmArea">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalArea">Nueva Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Area</label>
                            <input type="text" name="area" id="area" class="form-control" required maxlength="200">
                        </div>                        
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Encargado Nombres</label>
                            <input type="text" name="nombres" id="nombres" class="form-control" required maxlength="50">
                        </div>                        
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Encargado Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" required maxlength="50">
                        </div>                        
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Encargado DNI</label>
                            <input type="text" name="dni" id="dni" class="form-control numerosindecimal" required maxlength="8">
                        </div>                        
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idarea" id="idarea">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="submit" id="btnArea">
                    Guardar
                </button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
$(function(){
    $(".numerosindecimal").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $("#frmArea").on('submit', function(e){
        e.preventDefault();
        let btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>',
            textBtn = $('#btnArea').text();
        $("#btnArea").attr('disabled', 'disabled');
        $("#btnArea").html(`${btnHTML} Guardando`);
        $.ajax({
            method: 'POST',
            url: 'salida/saveUpdateArea',
            data: $(this).serialize(),
            success: function(data){
                if(data == 'insert' || data == 'update'){
                    location.reload();
                }else{
                    if( data == 'existe' )
                        swal_alert('Mensaje', 'Ya existe el área', 'warning', 'Aceptar');

                    $("#btnArea").removeAttr('disabled');
                    $("#btnArea").text(textBtn);
                }
                //console.log(data);
            }
        });
    })

    $("#tblAreas").on('click', '.editArea', function(e){
        e.preventDefault();
        let idarea = $(this).attr('idarea'),
            area = $(this).attr('area'),
            nombres = $(this).attr('nombres'),
            apellidos = $(this).attr('apellidos'),
            dni = $(this).attr('dni');
        
        modalOption('edit');
        $("#idarea").val(idarea);
        $("#area").val(area);
        $("#nombres").val(nombres);
        $("#apellidos").val(apellidos);
        $("#dni").val(dni);
        $("#modalArea").modal();
    });

    $("#tblAreas").on('click', '.deleteArea', function(e){
        e.preventDefault();
        let idarea = $(this).attr('idarea'),
            area = $(this).attr('area');
        
        //console.log(idcategoria, categoria);
        let objConfirm = {
            title: '¿Estás seguro?',
            text: "Vas a eliminar el área: "+area,
            icon: 'warning',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('salida/eliminarArea', {
                    idarea:idarea
                }, function(data){
                    console.log(data);
                    if(data == "delete"){
                        location.reload();
                    }else if(data > 0){
                        swal_alert('No puedes eliminar el área', 'El área a eliminar tiene ' + data + ' salida(s)', 'info', 'Aceptar');
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
    });

    $("#modalArea").on('hide.bs.modal', function(){
        modalOption('new');
    });
});

function modalOption(opt){
    $("#idarea").val("");
    $("#area").val("");
    $("#nombres").val("");
    $("#apellidos").val("");
    $("#dni").val("");

    if(opt == 'new'){
        $("#titleModalArea").text("Nueva Area");
        $("#btnArea").text("Guardar");
    }else if(opt == 'edit'){    
        $("#titleModalArea").text("Editar Area");
        $("#btnArea").text("Editar");
    }
}
</script>