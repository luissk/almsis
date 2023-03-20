<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-12">
                <h3 class="m-0 text-dark">Categorías</h3>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCategoria">
                    Nueva Categoría
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed dt-responsive tablas" width="100%" id="tblCategorias">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>CATEGORIA</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($categorias){
                        foreach($categorias as $cat){
                        echo "<tr>
                            <td>$cat[idcategoria]</td>
                            <td>$cat[categoria]</td>
                            <td>
                                <button title='editar' class='btn btn-success btn-sm editCategoria' idcat=$cat[idcategoria] cat='$cat[categoria]'>
                                    editar
                                </button>
                                <button title='eliminar' class='btn btn-danger btn-sm deleteCategoria' idcat=$cat[idcategoria] cat='$cat[categoria]'>
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
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="titleModalCategoria" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="frmCategoria">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalCategoria">Nueva Categoría</h5>
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
                        <input type="text" class="form-control" placeholder="Categoría" name="categoria" id="categoria" required>
                        <input type="hidden" name="idcategoria" id="idcategoria">
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="submit" id="btnCategoria">
                    Guardar
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    $("#frmCategoria").on('submit', function(e){
        e.preventDefault();
        let btnHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>',
            textBtn = $('#btnCategoria').text();
        $("#btnCategoria").attr('disabled', 'disabled');
        $("#btnCategoria").html(`${btnHTML} Guardando`);
        $.ajax({
            method: 'POST',
            url: 'producto/saveUpdateCategoria',
            data: $(this).serialize(),
            success: function(data){
                if(data == 'insert' || data == 'update'){
                    location.reload();
                }else{
                    $("#btnCategoria").removeAttr('disabled');
                    $("#btnCategoria").text(textBtn);
                }
            }
        });
    })

    $("#tblCategorias").on('click', '.editCategoria', function(e){
        e.preventDefault();
        let idcategoria = $(this).attr('idcat'),
            categoria = $(this).attr('cat');
        
        modalOption('edit');
        $("#categoria").val(categoria);
        $("#idcategoria").val(idcategoria);
        $("#modalCategoria").modal();
    });

    $("#tblCategorias").on('click', '.deleteCategoria', function(e){
        e.preventDefault();
        let idcategoria = $(this).attr('idcat'),
            categoria = $(this).attr('cat');
        
        //console.log(idcategoria, categoria);
        let objConfirm = {
            title: '¿Estás seguro?',
            text: "Vas a eliminar la categoría: "+categoria,
            icon: 'warning',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No',
            funcion: function(){
                $.post('producto/eliminarCategoria', {
                    idcategoria:idcategoria
                }, function(data){
                    console.log(data);
                    if(data == "delete"){
                        location.reload();
                    }else if(data > 0){
                        swal_alert('No puedes eliminar la categoría', 'La categoría a eliminar tiene ' + data + ' producto(s)', 'info', 'Aceptar');
                    }
                });
            }
        }            
        swal_confirm(objConfirm);
    });

    $("#modalCategoria").on('hide.bs.modal', function(){
        modalOption('new');
    });
});

function modalOption(opt){
    if(opt == 'new'){
        $("#titleModalCategoria").text("Nueva Categoría");
        $("#categoria").val("");
        $("#idcategoria").val("");
        $("#btnCategoria").text("Guardar");
    }else if(opt == 'edit'){    
        $("#titleModalCategoria").text("Editar Categoría");
        $("#categoria").val("");
        $("#idcategoria").val("");
        $("#btnCategoria").text("Editar");
    }
}
</script>