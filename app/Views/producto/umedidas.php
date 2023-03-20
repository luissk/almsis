<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Unidades de Medida</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content bg-white">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalMedida">
                    Nueva Medida
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive tablas" width="100%" id="tblMedidas">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>U MEDIDA</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($medidas){
                        foreach($medidas as $cat){
                        echo "<tr>
                            <td>$cat[idum]</td>
                            <td>$cat[um]</td>
                            <td>
                                <button title='editar' class='btn btn-success btn-sm editMedida' idum=$cat[idum] medida='$cat[um]'>
                                    editar
                                </button>
                                <button title='eliminar' class='btn btn-danger btn-sm deleteMedida' idum=$cat[idum] medida='$cat[um]'>
                                    eliminar
                                </button>
                            </td>
                        </tr>";
                        }
                    }
                    ?>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>


<!-- Modal Nueva Categoria-->
<div class="modal fade" id="modalMedida" tabindex="-1" aria-labelledby="titleModalMedida" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="frmMedida">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalMedida">Nueva Medida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">              
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="UMedida" name="medida" id="medida" required>
                        <input type="hidden" name="idum" id="idum">
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="submit" id="btnMedida">
                    Guardar
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    $("#frmMedida").on('submit', function(e){
        e.preventDefault();
        let btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>',
            textBtn = $('#btnMedida').text();
        $("#btnMedida").attr('disabled', 'disabled');
        $("#btnMedida").html(`${btnHTML} Guardando`);
        $.ajax({
            method: 'POST',
            url: 'producto/saveUpdateMedida',
            data: $(this).serialize(),
            success: function(data){
                if(data == 'insert' || data == 'update'){
                    location.reload();
                }else{
                    $("#btnMedida").removeAttr('disabled');
                    $("#btnMedida").text(textBtn);
                }
            }
        });
    })

    $("#tblMedidas").on('click', '.editMedida', function(e){
        e.preventDefault();
        let idum = $(this).attr('idum'),
            medida = $(this).attr('medida');
        
        modalOption('edit');
        $("#medida").val(medida);
        $("#idum").val(idum);
        $("#modalMedida").modal();
    });

    $("#tblMedidas").on('click', '.deleteMedida', function(e){
        e.preventDefault();
        let idum = $(this).attr('idum'),
            medida = $(this).attr('medida');
        
        let objConfirm = {
            title: '¿Estás seguro?',
            text: "Vas a eliminar la medida: "+medida,
            icon: 'warning',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('producto/eliminarMedida', {
                    idum:idum
                }, function(data){
                    console.log(data);
                    if(data == "delete"){
                        location.reload();
                    }else if(data > 0){
                        swal_alert('No puedes eliminar la medida', 'La medida a eliminar tiene ' + data + ' producto(s)', 'info', 'Aceptar');
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
    });

    $("#modalMedida").on('hide.bs.modal', function(){
        modalOption('new');
    });
});

function modalOption(opt){
    if(opt == 'new'){
        $("#titleModalMedida").text("Nueva Medida");
        $("#medida").val("");
        $("#idum").val("");
        $("#btnMedida").text("Guardar");
    }else if(opt == 'edit'){    
        $("#titleModalMedida").text("Editar Medida");
        $("#medida").val("");
        $("#idum").val("");
        $("#btnMedida").text("Editar");
    }
}
</script>